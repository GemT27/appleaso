package main

import (
	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"
	"http/cache"
	"http/util"
	"net/http"
	"os"
	"sync"
)

type Args struct {
	Term    string `json:"term" binding:"required" form:"term"`
	Country string `json:"country" binding:"required" form:"country"`
}

func main() {
	go cache.LevelInit()
	//gin.SetMode(gin.ReleaseMode)
	godotenv.Load()
	r := gin.Default()
	r.Use(Cors())
	r.POST("/api/search", func(c *gin.Context) {
		arg := Args{}
		if err := c.ShouldBind(&arg); err == nil {
			_, err := cache.LevelDB.Get([]byte(arg.Country), nil)
			if err != nil {
				c.JSON(200, gin.H{
					"code": "400",
					"err":  err.Error(),
				})
				return
			}
			var wg sync.WaitGroup
			var info []util.INFO
			var hot []util.Resu
			wg.Add(2)
			go func() {
				defer wg.Done()
				info, err = util.GetInfo(arg.Term, arg.Country)
				if err != nil {
					info = nil
				}
			}()
			go func() {
				defer wg.Done()
				hot, err = util.KeyHot(arg.Term, arg.Country)
				if err != nil {
					hot = nil
				}
			}()
			wg.Wait()
			c.JSON(200, gin.H{
				"code": "200",
				"info": info,
				"key":  hot,
			})
		}
	})
	r.Run(":" + os.Getenv("HPORT"))
}
func Cors() gin.HandlerFunc {
	return func(c *gin.Context) {
		method := c.Request.Method
		c.Header("Access-Control-Allow-Origin", "*")
		c.Header("Access-Control-Allow-Headers", "Content-Type,AccessToken,X-CSRF-Token, Authorization, Token")
		c.Header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, PATCH, DELETE")
		c.Header("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Content-Type")
		c.Header("Access-Control-Allow-Credentials", "true")

		// 放行所有OPTIONS方法，因为有的模板是要请求两次的
		if method == "OPTIONS" {
			c.AbortWithStatus(http.StatusNoContent)
		}
		// 处理请求
		c.Next()
	}
}
