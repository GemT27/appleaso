package cache

import (
	"encoding/json"
	"github.com/syndtr/goleveldb/leveldb"
)

var LevelDB *leveldb.DB

// LevelDB 在中间件中初始化LevelDB创建
func LevelInit() {
	//返回的数据库实例对于并发使用是安全的。这意味着所有
	// DB的方法可以从多个goroutine同时调用。
	db, err := leveldb.OpenFile("leveldb/db", nil)
	if err != nil {
		panic(err)
	}
	_, err = db.Get([]byte("CA"), nil)
	if err != nil {
		var Countrys []Country
		json.Unmarshal([]byte(JsonStr), &Countrys)
		batch := new(leveldb.Batch)

		for key := range Countrys {
			batch.Put([]byte(Countrys[key].Info), []byte(Countrys[key].Storefront))
		}
		err3 := db.Write(batch, nil)
		if err3 != nil {
			panic(err3)
		}
	}
	LevelDB = db
}

type Country struct {
	Storefront string `json:"storefront"`
	Info       string `json:"info"`
}
const JsonStr  =`[
  {
    "storefront": "143455-6,32",
    "info": "CA"
  },
  {
    "storefront": "143441-1,32",
    "info": "US"
  },
  {
    "storefront": "143575-2,32",
    "info": "AL"
  },
  {
    "storefront": "143565-2,32",
    "info": "BY"
  },
  {
    "storefront": "143446-2,32",
    "info": "BE"
  },
  {
    "storefront": "143526-2,32",
    "info": "BG"
  },
  {
    "storefront": "143494-2,32",
    "info": "HR"
  },
  {
    "storefront": "143557-2,32",
    "info": "CY"
  },
  {
    "storefront": "143489-2,32",
    "info": "CZ"
  },
  {
    "storefront": "143458-2,32",
    "info": "DK"
  },
  {
    "storefront": "143443-4,32",
    "info": "DE"
  },
  {
    "storefront": "143454-8,32",
    "info": "ES"
  },
  {
    "storefront": "143518-2,32",
    "info": "EE"
  },
  {
    "storefront": "143447-2,32",
    "info": "FI"
  },
  {
    "storefront": "143442-3,32",
    "info": "FR"
  },
  {
    "storefront": "143448-2,32",
    "info": "GR"
  },
  {
    "storefront": "143482-2,32",
    "info": "HU"
  },
  {
    "storefront": "143558-2,32",
    "info": "IS"
  },
  {
    "storefront": "143449-2,32",
    "info": "IE"
  },
  {
    "storefront": "143450-7,32",
    "info": "IT"
  },
  {
    "storefront": "143519-2,32",
    "info": "LV"
  },
  {
    "storefront": "143520-2,32",
    "info": "LT"
  },
  {
    "storefront": "143451-2,32",
    "info": "LU"
  },
  {
    "storefront": "143521-2,32",
    "info": "MT"
  },
  {
    "storefront": "143523-2,32",
    "info": "MD"
  },
  {
    "storefront": "143452-10,32",
    "info": "NL"
  },
  {
    "storefront": "143530-2,32",
    "info": "MK"
  },
  {
    "storefront": "143457-2,32",
    "info": "NO"
  },
  {
    "storefront": "143445-4,32",
    "info": "AT"
  },
  {
    "storefront": "143478-2,32",
    "info": "PL"
  },
  {
    "storefront": "143453-24,32",
    "info": "PT"
  },
  {
    "storefront": "143487-2,32",
    "info": "RO"
  },
  {
    "storefront": "143459-57,32",
    "info": "CH"
  },
  {
    "storefront": "143496-2,32",
    "info": "SK"
  },
  {
    "storefront": "143499-2,32",
    "info": "SI"
  },
  {
    "storefront": "143456-17,32",
    "info": "SE"
  },
  {
    "storefront": "143480-2,32",
    "info": "TR"
  },
  {
    "storefront": "143492-2,32",
    "info": "UA"
  },
  {
    "storefront": "143444-2,32",
    "info": "GB"
  },
  {
    "storefront": "143469-16,32",
    "info": "RU"
  },
  {
    "storefront": "143563-2,32",
    "info": "DZ"
  },
  {
    "storefront": "143564-2,32",
    "info": "AO"
  },
  {
    "storefront": "143524-2,32",
    "info": "AM"
  },
  {
    "storefront": "143568-2,32",
    "info": "AZ"
  },
  {
    "storefront": "143559-2,32",
    "info": "BH"
  },
  {
    "storefront": "143576-2,32",
    "info": "BJ"
  },
  {
    "storefront": "143525-2,32",
    "info": "BW"
  },
  {
    "storefront": "143578-2,32",
    "info": "BF"
  },
  {
    "storefront": "143580-2,32",
    "info": "CV"
  },
  {
    "storefront": "143581-2,32",
    "info": "TD"
  },
  {
    "storefront": "143582-2,32",
    "info": "CG"
  },
  {
    "storefront": "143516-2,32",
    "info": "EG"
  },
  {
    "storefront": "143584-2,32",
    "info": "GM"
  },
  {
    "storefront": "143573-2,32",
    "info": "GH"
  },
  {
    "storefront": "143585-2,32",
    "info": "GW"
  },
  {
    "storefront": "143467-2,32",
    "info": "IN"
  },
  {
    "storefront": "143491-2,32",
    "info": "IL"
  },
  {
    "storefront": "143528-2,32",
    "info": "JO"
  },
  {
    "storefront": "143529-2,32",
    "info": "KE"
  },
  {
    "storefront": "143493-2,32",
    "info": "KW"
  },
  {
    "storefront": "143497-2,32",
    "info": "LB"
  },
  {
    "storefront": "143588-2,32",
    "info": "LR"
  },
  {
    "storefront": "143531-2,32",
    "info": "MG"
  },
  {
    "storefront": "143589-2,32",
    "info": "MW"
  },
  {
    "storefront": "143532-2,32",
    "info": "ML"
  },
  {
    "storefront": "143590-2,32",
    "info": "MR"
  },
  {
    "storefront": "143533-2,32",
    "info": "MU"
  },
  {
    "storefront": "143593-2,32",
    "info": "MZ"
  },
  {
    "storefront": "143594-2,32",
    "info": "NA"
  },
  {
    "storefront": "143534-2,32",
    "info": "NE"
  },
  {
    "storefront": "143561-2,32",
    "info": "NG"
  },
  {
    "storefront": "143562-2,32",
    "info": "OM"
  },
  {
    "storefront": "143498-2,32",
    "info": "QA"
  },
  {
    "storefront": "143598-2,32",
    "info": "ST"
  },
  {
    "storefront": "143479-2,32",
    "info": "SA"
  },
  {
    "storefront": "143535-2,32",
    "info": "SN"
  },
  {
    "storefront": "143599-2,32",
    "info": "SC"
  },
  {
    "storefront": "143600-2,32",
    "info": "SL"
  },
  {
    "storefront": "143472-2,32",
    "info": "ZA"
  },
  {
    "storefront": "143602-2,32",
    "info": "SZ"
  },
  {
    "storefront": "143572-2,32",
    "info": "TZ"
  },
  {
    "storefront": "143536-2,32",
    "info": "TN"
  },
  {
    "storefront": "143481-2,32",
    "info": "AE"
  },
  {
    "storefront": "143537-2,32",
    "info": "UG"
  },
  {
    "storefront": "143571-2,32",
    "info": "YE"
  },
  {
    "storefront": "143605-2,32",
    "info": "ZW"
  },
  {
    "storefront": "143460-27,32",
    "info": "AU"
  },
  {
    "storefront": "143577-2,32",
    "info": "BT"
  },
  {
    "storefront": "143560-2,32",
    "info": "BN"
  },
  {
    "storefront": "143579-2,32",
    "info": "KH"
  },
  {
    "storefront": "143583-2,32",
    "info": "FJ"
  },
  {
    "storefront": "143476-2,32",
    "info": "ID"
  },
  {
    "storefront": "143517-2,32",
    "info": "KZ"
  },
  {
    "storefront": "143586-2,32",
    "info": "KG"
  },
  {
    "storefront": "143587-2,32",
    "info": "LA"
  },
  {
    "storefront": "143473-2,32",
    "info": "MY"
  },
  {
    "storefront": "143591-2,32",
    "info": "FM"
  },
  {
    "storefront": "143592-2,32",
    "info": "MN"
  },
  {
    "storefront": "143484-2,32",
    "info": "NP"
  },
  {
    "storefront": "143461-27,32",
    "info": "NZ"
  },
  {
    "storefront": "143477-2,32",
    "info": "PK"
  },
  {
    "storefront": "143595-2,32",
    "info": "PW"
  },
  {
    "storefront": "143597-2,32",
    "info": "PG"
  },
  {
    "storefront": "143474-2,32",
    "info": "PH"
  },
  {
    "storefront": "143601-2,32",
    "info": "SB"
  },
  {
    "storefront": "143486-2,32",
    "info": "LK"
  },
  {
    "storefront": "143603-2,32",
    "info": "TJ"
  },
  {
    "storefront": "143475-2,32",
    "info": "TH"
  },
  {
    "storefront": "143604-2,32",
    "info": "TM"
  },
  {
    "storefront": "143566-2,32",
    "info": "UZ"
  },
  {
    "storefront": "143471-2,32",
    "info": "VN"
  },
  {
    "storefront": "143466-13,32",
    "info": "KR"
  },
  {
    "storefront": "143465-19,32",
    "info": "CN"
  },
  {
    "storefront": "143470-18,32",
    "info": "TW"
  },
  {
    "storefront": "143464-19,32",
    "info": "SG"
  },
  {
    "storefront": "143462-9,32",
    "info": "JP"
  },
  {
    "storefront": "143515-45,32",
    "info": "MO"
  },
  {
    "storefront": "143463-45,32",
    "info": "HK"
  },
  {
    "storefront": "143538-2,32",
    "info": "AI"
  },
  {
    "storefront": "143540-2,32",
    "info": "AG"
  },
  {
    "storefront": "143505-28,32",
    "info": "AR"
  },
  {
    "storefront": "143539-2,32",
    "info": "BS"
  },
  {
    "storefront": "143541-2,32",
    "info": "BB"
  },
  {
    "storefront": "143555-2,32",
    "info": "BZ"
  },
  {
    "storefront": "143542-2,32",
    "info": "BM"
  },
  {
    "storefront": "143556-28,32",
    "info": "BO"
  },
  {
    "storefront": "143503-15,32",
    "info": "BR"
  },
  {
    "storefront": "143543-2,32",
    "info": "VG"
  },
  {
    "storefront": "143544-2,32",
    "info": "KY"
  },
  {
    "storefront": "143483-28,32",
    "info": "CL"
  },
  {
    "storefront": "143501-28,32",
    "info": "CO"
  },
  {
    "storefront": "143495-28,32",
    "info": "CR"
  },
  {
    "storefront": "143545-2,32",
    "info": "DM"
  },
  {
    "storefront": "143509-28,32",
    "info": "EC"
  },
  {
    "storefront": "143506-28,32",
    "info": "SV"
  },
  {
    "storefront": "143546-2,32",
    "info": "GD"
  },
  {
    "storefront": "143504-28,32",
    "info": "GT"
  },
  {
    "storefront": "143553-2,32",
    "info": "GY"
  },
  {
    "storefront": "143510-28,32",
    "info": "HN"
  },
  {
    "storefront": "143511-2,32",
    "info": "JM"
  },
  {
    "storefront": "143468-28,32",
    "info": "MX"
  },
  {
    "storefront": "143547-2,32",
    "info": "MS"
  },
  {
    "storefront": "143512-28,32",
    "info": "NI"
  },
  {
    "storefront": "143485-28,32",
    "info": "PA"
  },
  {
    "storefront": "143513-28,32",
    "info": "PY"
  },
  {
    "storefront": "143507-28,32",
    "info": "PE"
  },
  {
    "storefront": "143508-28,32",
    "info": "DO"
  },
  {
    "storefront": "143548-2,32",
    "info": "KN"
  },
  {
    "storefront": "143549-2,32",
    "info": "LC"
  },
  {
    "storefront": "143550-2,32",
    "info": "VC"
  },
  {
    "storefront": "143554-2,32",
    "info": "SR"
  },
  {
    "storefront": "143551-2,32",
    "info": "TT"
  },
  {
    "storefront": "143552-2,32",
    "info": "TC"
  },
  {
    "storefront": "143514-2,32",
    "info": "UY"
  },
  {
    "storefront": "143502-28,32",
    "info": "VE"
  }
]
`

