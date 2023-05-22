import sys
import codecs
sys.stdout = codecs.getwriter("utf-8")(sys.stdout.detach())
import unicodedata
import pandas as pd
import string

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
    filter_ = []

    if sys.argv[1] == 'location':
        location = str(sys.argv[2]).lower()

        
    if len(sys.argv) > 3 and sys.argv[3] == 'filter':
        with open('./data/filter.txt', 'r') as f:
            filter_ = [i.strip() for i in f.readlines()]

    exceptions = ['pharmacies open today']

    punctuation = string.punctuation

    punctuation += '‘'
    punctuation += '’'

        
    with open('./data/locations.txt', 'r', encoding='utf-8') as f:
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
        df = pd.read_csv('./data/location_articles_images.csv')
        articles = []

        for i, row in df.iterrows():
            cont = True
            if original in eval(row['location']):
                title = ''.join([i for i in row['title'] if i not in punctuation]).lower()
                title = title.split()

                for word in exceptions:
                    if word in title:
                        cont = False

                for word in filter_:
                    if word in title:
                        cont = False
                
                if cont:
                    if 'https://newsbook.com.mt/' in row['link'] :
                        source = 'Newsbook'
                    elif 'https://www.maltatoday.com.mt//' in row['link']:
                        source = 'MaltaToday'
                    elif 'https://www.independent.com.mt/' in row['link']:
                        source = 'The Malta Independent'

                    if pd.isnull(row['image']) or row['image'].startswith('data'):
                        if 'https://newsbook.com.mt/' in row['link'] :
                            row['image'] = './images/newsbook.png'
                        elif 'https://www.maltatoday.com.mt//' in row['link']:
                            row['image'] = './images/maltatoday.png'
                        elif 'https://www.independent.com.mt/' in row['link']:
                            row['image'] = './images/maltaindependent.png'


                    # capitalise last two letters of date
                    date = row['date']
                    date = date[:-2] + date[-2:].upper()

                    articles.append((row['link'], row['title'], date + ' | ' + source, row['image']))

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
