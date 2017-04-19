# CiDOS-Notes-Downloader (beta)
CiDOS Notes Downloader written in PHP (CLI)

Only for PBU students for now.

## Version
Last Update : 20/04/2017

- 1.00 
  - cidos.php created and authentication login lmspbu using cURL. 
- 1.10 
  - Added regular expression for grabbing all the student course title and link
- 1.11 
  - Spelling correction.
- 1.20 
  - Added list of output student course.
- 1.21 
  - Fix some indentation.
- 1.22 
  - Added fgets function for course selection.
- 1.30
  - Correcting some misspelling lol .
  - Added list of course notes.
  - Added fgets function for notes selection.
  - Checking mime types using curlinfo content type.
  - Notes can be download now. (only for presentation format only).
  - File extension : .ppt
- 1.31
  - Display account name
- 1.40
  - Detect mime type using mime_content_type instead of curl_getinfo.
  - No need to download file 2 times for content and it only mime types (refer top)
  - Add file extension : .doc .rtf .xls .pptx .docx
## Screenshot
![image](http://i.imgur.com/R0NwYqa.png)

## To Do List

- [x] Add account name
- [ ] Add error handler
- [ ] Add user defined function for more arranged
- [ ] Add various type of file extension
- [ ] Change notes section regex pattern
