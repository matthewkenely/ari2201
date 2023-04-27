import sys
import codecs
sys.stdout = codecs.getwriter("utf-8")(sys.stdout.detach())

from bs4 import BeautifulSoup as bs
from urllib import request
import requests

import spacy
nlp = spacy.load('en_core_web_sm')

def get_location(content):
    locations = []
    for i in content:
        # print(i)
        doc = nlp(str(i))
        for ent in doc.ents:
            if ent.label_ == 'GPE':
                locations.append(ent.text)
                
    counts = {}
    for location in locations:
        counts[location] = counts.get(location, 0) + 1

    # print(counts)
    return max(counts, key=counts.get)


if __name__ == '__main__':
    url = None
    text = None
    content = None

    if sys.argv[1] == 'url':
        url = str(sys.argv[2])
    elif sys.argv[1] == 'text':
        text = str(sys.argv[2])

    try:
        if url:
            if 'http' not in url:
                url = 'https://' + url

            headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36'}

            source = requests.get(url, headers=headers, verify=False).text
            soup = bs(source, 'html.parser', from_encoding='utf-8')

            content = soup.find_all('p')
            content += soup.find_all('h1')
            content += soup.find_all('h2')
            content += soup.find_all('h3')

            content = [i.text for i in content]
        elif text:
            content = [text]

        output = get_location(content)
        print(output)
    except Exception as e:
        print('Unable to detect location.')
        print(e)