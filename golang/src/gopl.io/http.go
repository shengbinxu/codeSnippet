package main

// https://blog.narenarya.in/concurrent-http-in-go.html

import (
	// "encoding/json"
	"bufio"
	. "fmt"
	"io/ioutil"
	"log"
	"net/http"
	"net/url"
	"os"
	"strings"
	"time"
)

func main() {
	ch := make(chan string)
	start := time.Now()

	names, _ := readLines("entity_list.txt")

	limit := 30 // 每个请求拉取30个设备的轨迹
	total_requests := 0
	for start := 0; start < len(names); start += limit {
		per_request_names := names[start : start+limit]
		names_str := strings.Join(per_request_names, ",")
		// 并发
		go request(names_str, ch)
		total_requests++
	}
	for i := 0; i < total_requests; i++ {
		Println(<-ch)
	}
	Printf("%.2fs elapsed\n", time.Since(start).Seconds())
}

func request(entityNames string, ch chan<- string) []byte {
	client := http.Client{}

	request, err := http.NewRequest("GET", "http://yingyan.baidu.com/api/v3/entity/list", nil)

	if err != nil {
		Println(err)
		os.Exit(1)
	}

	v := url.Values{}
	v.Set("service_id", "148354")
	v.Set("ak", "8tSpP6iDM28AUG5CnRyt4BouaZPns5Z7")
	v.Set("filter", "entity_names:"+entityNames)
	v.Set("page_size", "1000")

	request.URL.RawQuery = v.Encode()
	log.Println(request.URL.String())

	response, err := client.Do(request)
	if err != nil {
		Println(err)
		os.Exit(1)
	}
	// Println(response.StatusCode)
	body, err := ioutil.ReadAll(response.Body)
	// log.Printf("%s", body)
	ch <- Sprintf("%s", body)
	return body
}

func readLines(path string) ([]string, error) {
	file, err := os.Open(path)
	if err != nil {
		return nil, err
	}
	defer file.Close()

	var lines []string
	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		lines = append(lines, scanner.Text())
	}
	return lines, scanner.Err()
}

// writeLines writes the lines to the given file.
func writeLines(lines []string, path string) error {
	file, err := os.Create(path)
	if err != nil {
		return err
	}
	defer file.Close()

	w := bufio.NewWriter(file)
	for _, line := range lines {
		Fprintln(w, line)
	}
	return w.Flush()
}
