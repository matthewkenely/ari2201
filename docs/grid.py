import sys
import codecs
sys.stdout = codecs.getwriter("utf-8")(sys.stdout.detach())
import unicodedata

import pandas as pd

def convert_maltese_characters(text):
    mapping = {
        'ġ': 'g',
        'ħ': 'h',
        'ż': 'z',
        'ċ': 'c',
        'Ġ': 'G',
        'Ħ': 'H',
        'Ż': 'Z',
        'Ċ': 'C'
    }

    converted_text = ''.join([mapping.get(char, char) for char in text])
    return converted_text

if __name__ == '__main__':
    location = None

    if sys.argv[1] == 'location':
        location = str(sys.argv[2]).lower()
        
    with open('./code/locations.txt', 'r', encoding='utf-8') as f:
        locations = f.readlines()
        original_locations = locations
        locations = [convert_maltese_characters(x) for x in locations]
        locations = [x.strip().lower() for x in locations]

    location = convert_maltese_characters(location)

    if location in locations:
        index = locations.index(location)
        original = original_locations[index].strip()
        print('{')
        print('"location": "' + original + '",')


        # return articles in location_articles.csv which contain this location in the location column
        df = pd.read_csv('./code/location_articles_images.csv')
        articles = []

        for i, row in df.iterrows():
            if original in eval(row['location']):
                if pd.isnull(row['image']) or row['image'].startswith('data'):
                    row['image'] = './images/newsbook.png'
                articles.append((row['link'], row['title'], row['date'], row['image']))

        # print(articles)
        print('"articles" : [')
        for article in articles:
            print('{')
            print('"link": "' + article[0] + '",')
            print('"title": "' + article[1] + '",')
            print('"date": "' + article[2] + '",')
            print('"image": "' + article[3] + '"')
            print('},') if article != articles[-1] else print('}')

        print(']')
        print('}')
    else:
        print('Please enter a valid location')
