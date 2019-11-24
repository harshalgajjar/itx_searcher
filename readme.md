Team mates:
160010003 Harshal Gajjar (100% contribution)

Topic: itx search engine

NOTE: there were escape characters used at several places in the itx, for example: in https://sanskritdocuments.org/doc_veda/udakashaanti.itx nama\`ste is present, but the itx documentation doesn't contain any character like \` (without the backward slash) and hence it has been removed while processing, i.e nama\`ste is referred to by 'namaste' while searching.

Brief the project(?)
It's a .itx search engine
  It was supposed to process the itx to allow users to download the pdfs but I need more time to understand the ./js/itrans.js without documentation (which doesn't exist). It was also going to process the input and convert it into ASCII using the same js file.

How to install it?
It doesn't use any database and hence doesn't need any special modifications in the code.
But it uses the following packages on server to generate PDFs:
  - itrans (https://ctan.org/pkg/itrans-processor)
  - tex (https://ctan.org/pkg/tex)
So, make sure these are installed for the pdf generation script to work.
Also, I've created all of the directories being used already, but if something doesn't work - adjust the permissions appropriately.

How to run it?
I've already crawled a few websites and processed a few itx files.
So, open index.php and type in ITRANS to get links containing the indian script word you entered

What do the files do?
  - crawler.php: processes ./queues/crawler_queue.txt (5 links at a time) to find new links to crawl and adds them to the same file, it also adds the itx links to ./queues/itx_queue.txt to be processed later by ./process_itx.php
  - index.php: takes in input from the user and shows search results by calling ./search.php through AJAX
  - process_itx.php: processes ./queues/itx_queue.txt (5 links at a time) to find new words, which when found are added to ./data/itx_words.txt and to ./data/itx_data.txt
  - search.php: provides suggestions while typing the form on index.php and also provides the final result
  - ./reset: it's an executable to reset all data collected by crawling. It adds "http://sanskritdocuments.org" to ./queues/crawler_queue.txt as a starting point. One can manually change the start by editing that file after resetting.

Can this do anything else?
It can index files of any given format available on www by crawling through websites
