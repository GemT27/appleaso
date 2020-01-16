package util

import (
	"errors"
	"fmt"
	"github.com/beevik/etree"
	"http/cache"
	"io/ioutil"
	"net/http"
	"net/url"
)

const Kurl = "https://search.itunes.apple.com/WebObjects/MZSearchHints.woa/wa/hints?clientApplication=Software&%s"

var Client = &http.Client{}

func KeyHot(term, country string) ([]Resu, error) {
	xml, err := KeyWordHot(term, country)
	if err != nil {
		return nil, err
	}
	result, err := DecodeXML(xml)
	if err != nil {
		return nil, err
	}
	//fmt.Println(result)
	return result, nil
}

func KeyWordHot(term, country string) ([]byte, error) {
	v := url.Values{}
	v.Add("term", term)
	res := v.Encode()
	req, err := http.NewRequest("GET", fmt.Sprintf(Kurl, res), nil)
	if err != nil {
		return nil, err
	}
	id, err := cache.LevelDB.Get([]byte(country), nil)
	if err != nil {
		return nil, err
	}
	req.Header.Set("X-Apple-Store-Front", fmt.Sprintf("%s-19,29", string(id[:6])))
	resp, err := Client.Do(req)
	if err != nil {
		return nil, err
	}
	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return nil, err
	}
	resp.Body.Close()
	return body, nil
}

func DecodeXML(xml []byte) ([]Resu, error) {
	doc := etree.NewDocument()
	if err := doc.ReadFromBytes(xml); err != nil {
		return nil, err
	}
	array := doc.SelectElement("plist").SelectElement("dict").SelectElement("array")
	//是否有热度
	if array.FindElement("dict") == nil {
		return nil, errors.New("not found")
	}
	var result []Resu
	for _, root := range array.SelectElements("dict") {
		name := root.SelectElement("string")
		hot := root.SelectElement("integer")
		if name != nil && hot != nil {
			result = append(result, Resu{name.Text(), hot.Text()})
		}
	}
	return result, nil
}

type Resu struct {
	Term string `json:"term"`
	Hot  string `json:"hot"`
}
