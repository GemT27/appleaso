package util

import (
	"bytes"
	"fmt"
	"github.com/PuerkitoBio/goquery"
	"http/cache"
	"io/ioutil"
	"net/http"
	"net/url"
)

const Info = "https://search.itunes.apple.com/WebObjects/MZSearch.woa/wa/search?startIndex=0&entity=software&media=software&page=1&restrict=false&prevIndex=300&%s"

func InfoQuery(term, country string) ([]byte, error) {
	v := url.Values{}
	v.Add("term", term)
	res := v.Encode()
	req, err := http.NewRequest("GET",
		fmt.Sprintf(Info, res), nil)
	if err != nil {
		return nil, err
	}
	id, err := cache.LevelDB.Get([]byte(country), nil)
	if err != nil {
		return nil, err
	}
	req.Header.Set("Host", "search.itunes.apple.com")
	req.Header.Set("Accept", "application/xml")
	req.Header.Set("Connection", "keep-alive")
	req.Header.Set("Proxy-Connection", "keep-alive")
	req.Header.Set("User-Agent", "iTunes/12.9.5 (Macintosh; OS X 10.14.5) AppleWebKit/607.2.6.1.1  (dt:1)")
	req.Header.Set("Accept-Language", "zh-CN,zh;q=0.9,en;q=0.8")
	req.Header.Set("X-Apple-Store-Front", string(id))
	req.Header.Set("cache-control", "max-age=0")
	resp, err := Client.Do(req)
	if err != nil {
		return nil, err
	}
	defer resp.Body.Close()
	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return nil, err
	}
	return body, nil
}

func DecodeHTML(html []byte) ([]INFO, error) {
	body := bytes.NewReader(html)
	doc, err := goquery.NewDocumentFromReader(body)
	if err != nil {
		return nil, err
	}
	var infos []INFO
	doc.Find(".paginated-content ").Each(func(i int, s *goquery.Selection) {
		s.Find("ul").Each(func(i int, selection *goquery.Selection) {
			href, _ := selection.Find(".name a").Attr("href")
			genre := selection.Find(".genre").Text()
			name := selection.Find(".name").Text()
			infos = append(infos, INFO{Href: href, ID: i + 1, Genre: genre, Name: name})
		})
		s.Find(".mobile-app-icon-clip img").Each(func(i int, selection *goquery.Selection) {
			logo, _ := selection.Attr("src-swap")
			infos[i].Logo = logo
		})
	})
	return infos, nil
}

func GetInfo(term, country string) ([]INFO, error) {
	body, err := InfoQuery(term, country)
	if err != nil {
		return nil, err
	}
	result, err := DecodeHTML(body)
	if err != nil {
		return nil, err
	}
	return result, nil
}

type INFO struct {
	ID    int    `json:"id"`
	Logo  string `json:"logo"`
	Name  string `json:"name"`
	Genre string `json:"genre"`
	Href  string `json:"href"`
}
