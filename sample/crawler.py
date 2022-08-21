import requests
from bs4 import BeautifulSoup
import json

# word = input()
url = "https://hylib.typl.gov.tw/bookSearchList.do?searchtype=simplesearch&execodeHidden=true&execode=&authoriz=1&search_field=TI&search_input=旅遊"
# url += word

# url = 'https://shopee.tw/api/v2/search_items/?by=relevancy&keyword=switch&limit=50'

# headers = {
#         'User-Agent': 'Googlebot',
#     }

response = requests.get(url)
html_doc = r.text

soup = BeautifulSoup(response.text, "lxml")
# api1_data = json.loads(r.text)xm3
list1 = soup.find_all('div',{'class': 'booklist'})
# 查看第8筆資料的內容:
# print(type(soup))
print(list1)

