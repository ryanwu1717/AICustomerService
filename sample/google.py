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
    # print()
    return assoiateArr
# data_utils.download_data_gdown("./")

getOld = rq.get('http://localhost/line/lastUpdated')
getOld=ast.literal_eval( getOld.text)

showDic = {}
ws = WS("./data")
pos = POS("./data")
ner = NER("./data")
rq.packages.urllib3.disable_warnings()


driver = webdriver.Firefox()
driver.get("https://trends.google.com.tw/trends/trendingsearches/daily?geo=TW")
delay = 3 # seconds
sleep(2)
arr=[]
sentence_list = []
existArr = []
  
print ("Page is ready!")
source = driver.page_source
btnMore = driver.find_elements_by_xpath("//i[@class='material-icons-extended size-24 gray']")
count=0
for tmpBtn in btnMore:
  sentence_list = []
  tmpBtn.click()
  source = driver.page_source
  soup = BeautifulSoup(source, "html.parser")

  # str1  = (soup.find('div','title').find('span').text).split()[count]
  str1 = soup.select('div.title')[count]
  str1 =  str1.select('span')[0]
  str1 =  str1.select('a')[0].get('title')
  str1 = str1.replace("探索", "")
  print(str1)

  # str1 = soup.select('div.feed-item-header')[count].text
  # str1 = str1.replace(" ", "")
  # str1 = str1.replace("\n", "")
  # arr.append(str1)
  # print(str1)
  # print(str1)

  assoiateWord = soup.select('div.list a')
  # print(assoiateWord)
  for item in assoiateWord:
    if(item.text not in getOld.keys()):
      strNews = item.text.replace(" ", "")
      strNews = strNews.replace("\n", "")
      existArr.append(strNews)
      sentence_list.append(strNews)
  # news = tmp.find('div','details-bottom').find('a').text
  moreNews = soup.select('div.item-title')
  # print(moreNews)
  for item in moreNews:
    if(item.text not in getOld.keys()):
      existArr.append(item.text)
      sentence_list.append(item.text)
  # print(news)
  if(len(sentence_list) == 0):
    break

  count+=1
  print(sentence_list)

  word_sentence_list = ws(
  sentence_list,
    # sentence_segmentation = True, # To consider delimiters
    # segment_delimiter_set = {",", "。", ":", "?", "!", ";"}), # This is the defualt set of delimiters
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
      if (str1 not in showDic):
        showDic[str1] = {}
        for item in resultArr:
          if(item != str1):
            showDic[str1][item] = 1
      else:
        for item in resultArr:
          if(item != str1):
            if(item in  showDic[str1]):
              showDic[str1][item] +=1
            else:
              showDic[str1][item] = 1
print(showDic)        
del ws
del pos
del ner

test = json.dumps(showDic,ensure_ascii=False)
# print(showDic)
test = test.replace("\'", "\"");
headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/ptt/news',  data=test.encode(), headers = headers)
# print(go.content)
driver.quit()  # remove this line to leave the browser open

test = json.dumps(existArr,ensure_ascii=False)

# test = test.replace("\'", "\"");
headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/line/lastUpdated',  data=test.encode(), headers = headers)
# print(existArr)