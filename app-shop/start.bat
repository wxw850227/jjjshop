
@echo off
:: nodejs安装目录下的nodevars.bat
set nodevars = "D:\Program Files\nodejs\nodevars.bat"
:: 切换到D盘
d:
:: 移动到需要启动的目录
cd xampp/product/jjj_shop_small/jjj_shop_shop
:: 启动项目
cmd /c %nodevars%&&npm run dev