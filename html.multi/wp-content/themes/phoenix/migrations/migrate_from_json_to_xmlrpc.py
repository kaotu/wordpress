import json
import re
import requests
from datetime import datetime

from wordpress_xmlrpc import Client, WordPressPost

from wordpress_xmlrpc.methods.posts import NewPost


from wordpress_xmlrpc.methods.media import UploadFile

from wordpress_xmlrpc.compat import xmlrpc_client

from wordpress_xmlrpc import WordPressTerm

from wordpress_xmlrpc.methods import taxonomies


wp = Client(
    'http://monitoring02.bypronto.com/xmlrpc.php',
    'princezidane',
    'Pradipat17!'
)
with open('blog.json') as f:
    data = json.load(f)
    host_url = 'http://www.sharecancersupport.org'
    category_dict = {}
    filter_time = datetime(2013, 12, 31, 23, 59, 59)
    print 'CREATE POST'
    for each in data['posts']:
        date_created = data['posts'][each]['start']
        date_created = datetime.strptime(
            date_created,
            '%Y-%m-%d %H:%M:%S'
        )
        if date_created > filter_time:
            content = data['posts'][each]['content']
            all_img_url = re.findall(
                r'(?<=<img src=")[a-z-/_\w\d]+\.(?:jpg|gif|png|jpeg)',
                content
            )

            for img_url in all_img_url:
                if not ('http' in img_url or 'https' in img_url):
                    # Upload Picture
                    request_url = host_url + img_url
                    response = requests.get(request_url)

                    if '.png' in img_url:
                        mime_type = 'image/png'
                    elif '.jpg' in img_url:
                        mime_type = 'image/jpeg'

                    file_name = img_url.split('/')[-1]

                    image_data = {
                        'name': file_name,
                        'type': mime_type,
                        'bits': xmlrpc_client.Binary(response.content)
                    }

                    uploaded_image = wp.call(UploadFile(image_data))
                    content = content.replace(img_url, uploaded_image['url'])

            title = data['posts'][each]['title']
            summary = data['posts'][each]['summary']
            date_modified = data['posts'][each]['modified']
            date_modified = datetime.strptime(
                date_modified,
                '%Y-%m-%d %H:%M:%S'
            )

            print title.encode('utf-8')

            post = WordPressPost()
            post.title = title
            post.content = content
            post.date_modified = date_modified
            post.date = date_created
            post.post_status = 'publish'
            post.excerpt = summary

            tags = data['posts'][each]['tag']
            if tags:
                for tag in tags:
                    tag_name = tags[tag].replace('\r', '')
                    if tag_name not in category_dict:
                        category = WordPressTerm()
                        category.taxonomy = 'category'
                        category.name = tag_name
                        category = wp.call(taxonomies.NewTerm(category))
                        category_dict[tag_name] = category

                    category = wp.call(
                        taxonomies.GetTerm('category', category_dict[tag_name])
                    )
                    post.terms.append(category)

            wp.call(NewPost(post))
