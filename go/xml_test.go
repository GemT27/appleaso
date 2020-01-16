package main

import (
	"fmt"
	"http/cache"
	. "http/util"
	"net/url"
	"testing"
)

var xml = `<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<!DOCTYPE plist PUBLIC "-//Apple Computer//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
    <dict>
        <key>title</key>
        <string>建议</string>
        <key>hints</key>
        <array>
            <dict>
                <key>term</key>
                <string>csgo</string>
                <key>priority</key>
                <integer>6859</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgo开箱模拟器</string>
                <key>priority</key>
                <integer>4649</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo%E5%BC%80%E7%AE%B1%E6%A8%A1%E6%8B%9F%E5%99%A8</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgo交易</string>
                <key>priority</key>
                <integer>4648</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo%E4%BA%A4%E6%98%93</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgo开箱</string>
                <key>priority</key>
                <integer>4618</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo%E5%BC%80%E7%AE%B1</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgo助手</string>
                <key>priority</key>
                <integer>4610</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo%E5%8A%A9%E6%89%8B</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgo战绩</string>
                <key>priority</key>
                <integer>4610</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo%E6%88%98%E7%BB%A9</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgo饰品</string>
                <key>priority</key>
                <integer>4609</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo%E9%A5%B0%E5%93%81</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgola</string>
                <key>priority</key>
                <integer>4606</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgola</string>
            </dict>
            <dict>
                <key>term</key>
                <string>csgo模拟器</string>
                <key>priority</key>
                <integer>4605</integer>
                <key>url</key>
                <string>https://search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=csgo%E6%A8%A1%E6%8B%9F%E5%99%A8</string>
            </dict>
            <dict>
                <key>term</key>
                <string>cs：go</string>
                <key>priority</key>
                <integer>4605</integer>
                <key>url</key>
                <string>https: //search.itunes.apple.com/WebObjects/MZStore.woa/wa/search?clientApplication=Software&amp;media=software&amp;src=hint&amp;submit=edit&amp;term=cs%EF%BC%9Ago</string>
            </dict>
        </array>
    </dict>
</plist>`

func TestDecodeXML(t *testing.T) {
	str, err := DecodeXML([]byte(xml))
	if err != nil {
		t.Log(err)
		return
	}
	fmt.Println(str)
}

const iphone = "Software"
const ipad = "iPadSoftware"

func init() {
	cache.LevelInit()
}

func TestUrl(t *testing.T) {
	v := url.Values{}
	v.Add("a", "a+b")
	body := v.Encode()
	fmt.Println(body)
}

func TestInfoQuery(t *testing.T) {
	html,err:=InfoQuery("微信", "CN")
	if err != nil {
		return
	}
	DecodeHTML(html)

}

func TestKeyWordHot(t *testing.T) {
	content, err := KeyWordHot("ab", "CN")
	if err != nil {
		fmt.Println(err)
	}
	fmt.Println(string(content))
}

func TestCountry(t *testing.T)  {
	v,_:=cache.LevelDB.Get([]byte("CA"),nil)
	fmt.Println(string(v))
}

func TestKeyHot(t *testing.T) {
	fmt.Println(KeyHot("CS","CN"))
}
