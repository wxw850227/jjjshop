
@echo off
:: nodejs��װĿ¼�µ�nodevars.bat
set nodevars = "D:\Program Files\nodejs\nodevars.bat"
:: �л���D��
d:
:: �ƶ�����Ҫ������Ŀ¼
cd xampp/product/jjj_shop_small/jjj_shop_shop
:: ������Ŀ
cmd /c %nodevars%&&npm run dev