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
# tmpDict = {1: ['暖陽好天氣肉豬吃多長胖 消費淡季挑戰豬價維穩', '潮濕天氣又來襲回南天抹屋秘技4招KO霉味毛菌！ - MAMA730網購- am730'], 2: ['【MPF】強積金人均帳戶結餘逾27萬元創新高中港股票基金表現最佳- 香港經濟日報- 即時新聞頻道- 即市財經- Hot Talk', '【 財政預算案2021】畢馬威：銀色債券年齡降可擴闊投資者基礎增股票印花稅可更快捷及簡便地提高收入- 香港經濟日報- 即時新聞頻道- 即市財經- Hot Talk', '香港會計師公會料政府上調股票印花稅每年可增逾200億元經常性收入', '港股“黑天鹅”：股票印花税提高30% 千只股票大跌', '【回應預算案】中原：股票買賣加印花稅料套股換樓增加', '英联股份(002846.SZ)69.65万股限制性股票将于2月26日上市流通', '《大行報告》摩通：港交所(00388.HK)因加股票印花稅受挫認為股價調整過度', '*ST宜生控股股东及实控人遭调查股票连 续下跌拟终止上市', '华兰生物：关于2018年限制性股票激励计划首次授予的第二期限制性股票解锁上市流通的提示性公告_股票频道', '兴化股份：股票交易异动，不存在未披露的重大信息| 每经网', '*ST当代：股票交易异动，不存在未披露的重大事项| 每经网', '桃李面包：第五期员工持股计划尚未持有公司股票', '特斯拉盘前涨超3%，此前获ARK投资管理公司增持11.5万股股票', '香港股市：恒指创 逾九个月来最大单日跌幅，受股票印花税上调影响', '快讯｜或受香港提高股票交易印花税影响恒指收跌逾3% 内房股、物业股板块飘绿_投融观察_地产频道首页_财经网- CAIJING.COM.CN', '陈茂波指是次调高股票印花税幅度只属轻微', '赞宇科技：关于增加公司注册资本并修订公司章程相应条款的公告_股票频道', '华脉科技：副总经理岳卫星辞职，未持有公司股票| 每经网', '我爱我家：关于公司实际 控制人所持股份解除质押及再质押的公告_股票频道', '银保监会保险监管主体职责改革再进一步_股票频道', '每经17点丨商务部：去年中国成为全球最大外资流入国；多只“巨无霸”个股今日现大宗交易；上交所受理*ST航通股票主动终止上市申请| 每经网', '步步高： 第六届董事会第六次会议决议公告_股票频道'], 3: [], 4: ['【最美凍齡主婦】蘇慧倫代言「婆媽市場咖啡」賺進300萬扒光兒子洗澡逼問小女朋友八卦--上報'], 5: ['納智捷合體鴻華先進推全球首創URX健康防疫特仕車｜ 蘋果新聞網｜ 蘋果日報', '教你檢查Mac SSD壽 命與健康度，用macOS終端機即可查詢', '摩洛哥橙子加肉桂蜂蜜 優雅而健康的早餐', '新年後的健康Tips： 4個Eat Clean習慣，讓飲 食重回正軌|健康好人生health', '境外生自主健康管理期間盡量避免進入校園-生活新聞', '余苑綺治療進度曝光余祥銓新年願望盼姊健康- 我的中時娛樂', '大健康國際中期虧損擴至2.6億人幣- 信報網站hkej.com', '大健康國際半年虧損擴至2.57億人幣', 'Uber Eats：農曆新年訂單按年增五成疫情下健康食品訂單量增8倍', '去年健康险保费同比增长15.66% 体量仅次于寿险和车险-新闻', '聚焦下沉市 场健康消费矛盾2021第六届西鼎会将于3月启幕_医疗_产经频道首页_财经网- CAIJING.COM.CN', '大健康国际(02211)中期股东应占亏损 约2.57亿元同比扩大约50.92%', '信隆健康(002105.SZ)2020年度归母净利润同比增长289.74%至1.63亿元', '铜川印台：卫健服务再升级用健康托起小康', '信隆健康：2020年度净利润约1.63亿元，同比增加289.74% | 每经网', '2020年非车险业务快速发展短期健康险业务贡献大_保险_金融频道首页', '大健康国际(02211-HK)2020年中期亏损扩大50.92% 不派息-手机金融界', '家用蒸箱哪款好能蒸出健康营养的才叫好_硬派科技', '开工大吉，阳光财险“爱满分”为“打工人”健康保驾护航，最低每天不到1元钱-工商资讯', '湖南疾控发布 健康提醒：莫把消毒产品当药品- 华声在线']}
tmpDict = {1: ['rrrrrr','111','11111','ttttt'],2: ['2','22222222'],3: ['333','33333333333333'],4: ['4','444','44'],5: ['555','55555555']}
test = json.dumps(tmpDict,ensure_ascii=False)


headers = {'Content-Type':'application/json; charest=utf-8'}
go = rq.post('http://localhost/reply/appear',  data=test.encode(), headers = headers)
print ((go.content).decode('utf-8'))
