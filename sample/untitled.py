# coding=utf-8
import requests as rq
from bs4 import BeautifulSoup
import jieba
import nltk
import datetime
import requests.packages.urllib3
import re
import math
from ckiptagger import data_utils, construct_dictionary, WS, POS, NER
import json 
import codecs 

def sortedDictValues2(adict): 
  keys = adict.keys() 
  keys.sort() 
  return [dict[key] for key in keys] 
def print_word_pos_sentence(word_sentence, pos_sentence):
    assert len(word_sentence) == len(pos_sentence)
    for word, pos in zip(word_sentence, pos_sentence):
        # print(f"{word}({pos})", end="\u3000")
        # print(pos)
        a = ['N', 'V']
        if any(x in pos for x in a) and len(pos)<5:
          # print(pos)

          if f"{word}" in weightDic:
            weightDic[f"{word}"] += 1
            # print(weightDic[f"{word}({pos})"])

          else:
            weightDic[f"{word}"] = 1

    print()
    return
# data_utils.download_data_gdown("./")



ws = WS("./data")
pos = POS("./data")
ner = NER("./data")
requests.packages.urllib3.disable_warnings()

tmpHref=''
count = 0
sentence_list = []
<<<<<<< HEAD
date_time_str = '2021-1-7'
=======
date_time_str = '2021-1-12'
>>>>>>> 552c474b3927fe168a05f75359b4bfc2d3435920
limitDate = datetime.datetime.strptime(date_time_str, '%Y-%m-%d')

weightDic = {}
urlArr = []

print(limitDate.date())

while True:
  
  payload = {
      'from' : '/bbs/NBA/index'+tmpHref+'.html',
      'yes' : 'yes'
  }
  rs = rq.session()
  url = "https://www.ptt.cc/bbs/NBA/index"+tmpHref+".html" # PTT NBA 板
  response = rs.post('https://www.ptt.cc/ask/over18', verify=False, data=payload)
  response = rs.get(url, verify=False) # 用 requests 的 get 方法把網頁抓下來
  html_doc = response.text # text 屬性就是 html 檔案
  soup = BeautifulSoup(html_doc, "html.parser") # 指定 lxml 作為解析器
  print(soup.select('.btn.wide')[1]['href'])
  tmpHref = soup.select('.btn.wide')[1]['href']
  tmpHref = tmpHref.split('/')
  # tmpHref.split('/')
  tmpHref = tmpHref[3].split('.')
  tmpHref = tmpHref[0].split('x')
  tmpHref = tmpHref[1]
  # rs.close()
  print(tmpHref)
  for entry in soup.select('.r-ent'):
    try:
    # print(entry.select('.date')[0].text,entry.select('.title')[0].text,entry.select('.author')[0].text)
      # print(entry.select('.title a')[0]['href'])
      
      tmpurl = 'https://www.ptt.cc/'+entry.select('.title a')[0]['href']
      response = rs.get(tmpurl, verify=False)
      artical = response.text
      articalSoup = BeautifulSoup(artical, "html.parser")
      # print(articalSoup.select('.article-metaline .article-meta-value')[2].text)
      tmpDate = (articalSoup.select('.article-metaline .article-meta-value')[2].text)
      # tmpDate = tmpDate.split(' ')
      tmpDate = re.split(r"  | ", tmpDate)
      tmpDate = tmpDate[4]+' '+tmpDate[1]+' '+tmpDate[2]
      tmpDate = datetime.datetime.strptime(tmpDate, '%Y %b %d')
      if(tmpDate>=limitDate):
        # print(string[3:5])
        urlArr.append(tmpurl)
        tmpStr = entry.select('.title')[0].text
        len(tmpStr)
        tmpStr = tmpStr[1:len(tmpStr)-1]

        sentence_list.append(tmpStr)
      else:
        count+=1
      # print(tmpDate)
      # print(date_time_obj.date())
    except Exception:
      pass
  # count = count+1
  # print(count)
  if count > 7:
    break
# print('printsentence_list')
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
    # print()
    # print(f"'{sentence}'")
    print_word_pos_sentence(word_sentence_list[i],  pos_sentence_list[i])
    for entity in sorted(entity_sentence_list[i]):
        print('',entity)
del ws
del pos
del ner
# print((weightDic))
# print(sorted(weightDic.items(), key=lambda d: d[0]) )
# print(sorted(weightDic.key()))
print(dict(reversed(sorted(weightDic.items(), key=lambda item: item[1],))))


showDic = {}
# existDic = {}
allDic = {}
for i in weightDic.keys():
  showDic[i] = 0
  # existDic[i] = 0
# for i in weightDic.keys():
#   allDic[i] = {}
#   allDic[i]['show'] = {}
#   allDic[i]['exist']  = 0
#   for j in weightDic.keys():

#     allDic[i]['show'][j] = 0
  # allDic[i]['show'].pop(i, None)

# print(allDic)


for forurl in urlArr:
  # print(forurl)
  response = rs.get(forurl, verify=False)
  artical = response.text
  articalSoup = BeautifulSoup(artical, "html.parser")
  deleteitem = articalSoup.select('.article-metaline')
  for item in deleteitem:
    item.decompose()
  articalSoup.select('.article-metaline-right')[0].decompose()
  tmpText = (articalSoup.find(id="main-content").text)
  # i_tag = articalSoup.find_all('.article-metaline')

  # i_tag.decompose()
  # tmpText.split('發信站')
  splitNum = tmpText.index('發信站')
  # print(tmpText[:splitNum])
  insideText = tmpText[:splitNum]
  tmpArr = []
  for item in weightDic.keys():
    try:
      if(insideText.index(item)>0):
        tmpArr.append(item)
        # print(item)
    except Exception as e:
      
      pass
  # print(tmpArr) 
  for item in tmpArr:
    showDic[item] +=1
    for showitem in tmpArr:
      if(item != showitem):
        if   (item not in allDic ):
          # print('in')
          allDic[item]={}
          # if  (showitem not in allDic[item]):
            # allDic[item]={}
          allDic[item][showitem] = 1
            # print(item,showitem)
        else:
          if  (showitem  in allDic[item]):
            allDic[item][showitem] += 1
          else :
            allDic[item][showitem] = 1


for key, value in allDic.items(): 
    # print(key, value)
    for keyvalue in value:
      tmppersent = math.floor(allDic[key][keyvalue] / showDic[key] *100)

      allDic[key][keyvalue] = tmppersent



test = json.dumps(allDic,ensure_ascii=False)

test = test.replace("\'", "\"");
print(test)

# f = open('text.text','w')
# f.write(test)
# f.close()

headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/ptt/news',  data=test.encode(), headers = headers)
print(go.content)



