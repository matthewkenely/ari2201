import sys
import codecs
sys.stdout = codecs.getwriter("utf-8")(sys.stdout.detach())
import unicodedata

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
        original = location
        
    with open('./code/locations.txt', 'r', encoding='utf-8') as f:
        locations = f.readlines()
        original_locations = locations
        locations = [convert_maltese_characters(x) for x in locations]
        locations = [x.strip().lower() for x in locations]

    location = convert_maltese_characters(location)

    if location in locations:
        index = locations.index(location)
        original = original_locations[index]
        print(original.capitalize())
    else:
        print('Please enter a valid location')
