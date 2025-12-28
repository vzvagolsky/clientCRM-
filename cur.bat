curl -X POST http://cli.crm/api/tickets ^
  -H "Accept: application/json" ^
  -F "email=vik@example.com" ^
  -F "phone=+49123456789" ^
  -F "name=Vik Ivanov" ^
  -F "subject=Problem with order" ^
  -F "message=Please help me" ^
  -F "attachments[]=@C:/Files/My/01.txt" ^
  -F "attachments[]=@C:/Files/My/02.txt"