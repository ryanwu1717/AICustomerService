# coding=utf-8

import requests as rq
from bs4 import BeautifulSoup
# import jieba
import nltk
import datetime
import requests.packages.urllib3
import re
import math
from ckiptagger import data_utils, construct_dictionary, WS, POS, NER
import json 
import codecs
from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.firefox.firefox_binary import FirefoxBinary
from time import sleep
import datetime
import ast



def sortedDictValues2(adict): 
  keys = adict.keys() 
  keys.sort() 
  return [dict[key] for key in keys] 
def print_word_pos_sentence(word_sentence, pos_sentence):
    assert len(word_sentence) == len(pos_sentence)
    assoiateArr=[]
    for word, pos in zip(word_sentence, pos_sentence):
        # print(f"{word}({pos})", end="\u3000")
        # print(pos)
        a = ['N', 'V']
        if any(x in pos for x in a) and len(pos)<5:
          # print(f"{word}")

          # if f"{word}" in weightDic:
          #   weightDic[f"{word}"] += 1
          #   # print(weightDic[f"{word}({pos})"])

          # else:
          #   weightDic[f"{word}"] = 1
          assoiateArr.append(f"{word}")
          Narr.append(f"{word}")
    # print()
    return assoiateArr
# data_utils.download_data_gdown("./")

Narr=[]
sentence_list = []
hrefArr = []
allDict={}
existDict={}
existArr=[]

rs = rq.session()

getOld = rq.get('http://localhost/line/lastUpdated')
getOld=ast.literal_eval( getOld.text)
# print(getOld['Test1'])


nowTime = datetime.datetime.now() 
nowTime = nowTime.strftime("%Y-%m/%d-%H:%M")
print("current time:-", nowTime)

ws = WS("./data")
pos = POS("./data")
ner = NER("./data")
requests.packages.urllib3.disable_warnings()

driver = webdriver.Firefox()
driver.get("https://today.line.me/tw/v2/tab")
sleep(2)
source = driver.page_source
soup = BeautifulSoup(source, "html.parser")



selectSoup = soup.select('div.carouselSecondary-slide h3')
swiperSoup = soup.select('div.carouselPrimaryWrapper div.swiper-wrapper a.swiper-slide')
count = 0
for item in selectSoup:

  # hrefArr.append(item['href'])
  # print(item['href'])
  strNews = item.text.replace(" ", "")
  strNews = strNews.replace("\n", "")
  try:
    if(strNews  in getOld.keys()):
      continue
  except:
    pass
  
  sentence_list.append(strNews)
  hrefArr.append(swiperSoup[count]['href'])
  existDict[strNews] = nowTime
  existArr.append(strNews)

  count +=1
# for item in swiperSoup:
#   # print(item['href'])
#   if(item['href'] not in getOld):
#     hrefArr.append(item['href'])

# print(hrefArr)
count=0
selectSoup = soup.select('div.listModule div.articleCard-content span.articleCard-title')
swiperSoup = soup.select('div.listModule a')
print(selectSoup)
print(swiperSoup)
for item in selectSoup:
  strNews = item.text.replace(" ", "")
  strNews = strNews.replace("\n", "")
  if(strNews not in getOld.keys()):
    sentence_list.append(strNews)

    hrefArr.append(swiperSoup[count]['href'])
    existDict[strNews] = nowTime
    existArr.append(strNews)
  count +=1

print(sentence_list)
# print(hrefArr)
print( existDict)
word_sentence_list = ws(
  sentence_list,
    # sentence_segmentation = True, # To consider delimiters
    # segment_delimiter_set = {",", "???", ":", "?", "!", ";"}), # This is the defualt set of delimiters
    # recommend_dictionary = dictionary1, # words in this dictionary are encouraged
    # coerce_dictionary = dictionary2, # words in this dictionary are forced
  )

pos_sentence_list = pos(word_sentence_list)

entity_sentence_list = ner(word_sentence_list, pos_sentence_list)
for i, sentence in enumerate(sentence_list):
    # print(arr[i])
    # print(f"'{sentence}'")

    resultArr = print_word_pos_sentence(word_sentence_list[i],  pos_sentence_list[i])
    # for entity in sorted(entity_sentence_list[i]):
    #     print('',entity)

    print(resultArr)
for tmpurl in hrefArr:
  detailStr=''
  print(tmpurl)
  response = rs.get(tmpurl, verify=False)
  artical = response.text
  articalSoup = BeautifulSoup(artical, "html.parser")
  title = articalSoup.find('h1','entityTitle')
  if(title is not None):
    detailStr += title.text
  
    # print(title.text)
    detail = articalSoup.select('article.news-content p')
    for item in detail:
      detailStr += item.text
  else:
    continue
    title = articalSoup.select('div.entityVideoInfo-title h2')
    detailStr += title[0].text
  print(detailStr)
  # print(Narr)
  tmpArr=[]
  if(len(tmpArr)>0):
    for item in Narr:
      try:
        if(detailStr.index(item)>0):
          tmpArr.append(item)
          # print(item)
      except Exception as e:
        
        pass
      
    print(tmpArr)
    for item1 in tmpArr:
      for item2 in tmpArr:
        if(item1 != item2):
          if   (item1 not in allDict ):
            allDict[item1]={}
            allDict[item1][item2] = 1
          else:
            if  (item2  in allDict[item1]):
              allDict[item1][item2] += 1
            else :
              allDict[item1][item2] = 1
print(allDict)
driver.quit()

del ws
del pos
del ner
test = json.dumps(allDict,ensure_ascii=False)

# test = test.replace("\'", "\"");
headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/ptt/news',  data=test.encode(), headers = headers)
# print(go.content)


test = json.dumps(existArr,ensure_ascii=False)

# test = test.replace("\'", "\"");
headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/line/lastUpdated',  data=test.encode(), headers = headers)
# print(go.content)


