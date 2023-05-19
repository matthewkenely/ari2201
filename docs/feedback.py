import sys
import codecs
sys.stdout = codecs.getwriter("utf-8")(sys.stdout.detach())
import unicodedata

import requests
from bs4 import BeautifulSoup as bs
import pandas as pd
import numpy as np
import re
import csv
import string

if __name__ == '__main__':
    link = None
    correct = None

    if sys.argv[1] == 'good':
        link = str(sys.argv[2]).lower()
        correct = str(sys.argv[3]).lower()
        
    if sys.argv[1] == 'bad':
        link = str(sys.argv[2]).lower()
        correct = str(sys.argv[3]).lower()

    print(link)
    print(correct)

    if sys.argv[1] == 'good':
        df = pd.read_csv('data/good.csv')
        df.loc[len(df)] = [link, correct]
        df.to_csv('data/good.csv', index=False)
        
    elif sys.argv[1] == 'bad':
        df = pd.read_csv('data/bad.csv')
        df.loc[len(df)] = [link, correct]
        df.to_csv('data/bad.csv', index=False)
