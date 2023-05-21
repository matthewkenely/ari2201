import sys
import codecs
sys.stdout = codecs.getwriter("utf-8")(sys.stdout.detach())

from bs4 import BeautifulSoup as bs
import requests

import re
import string

import spacy
nlp = spacy.load("./data/trained_model")

def get_location(content):
    doc = nlp(content)
    entities = [(ent.text, ent.label_) for ent in doc.ents]
    # print(entities)

    counts = {}

    for entity in entities:
        if entity[0] in counts:
            counts[entity[0]] += 1
        else:
            counts[entity[0]] = 1

    if len(counts) > 0:
        return max(counts, key=counts.get)
    else:
        return None


punctuation = string.punctuation.replace('-', '')
punctuation = punctuation.replace('\'', '')
punctuation += '\n'

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

            source = requests.get(url, headers=headers, verify=True).text
            soup = bs(source, 'html.parser')

            content = soup.find_all('p')
            content += soup.find_all('h1')
            content += soup.find_all('h2')
            content += soup.find_all('h3')

            body_text = ' '.join(p.text.strip() for p in content)
            body_cleaned = re.sub(r'[{}]'.format(re.escape(punctuation)), '', body_text)
        elif text:
            body_text = text.strip()
            body_cleaned = re.sub(r'[{}]'.format(re.escape(punctuation)), '', body_text)

        output = get_location(body_cleaned)

        if output is not None:
            print(output)
        else:
            print('Unable to detect location')
    except Exception as e:
        # print('Unable to detect location')
        # print(e)
        pass