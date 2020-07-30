user
'stocker'@'localhost' identified by 'stocker'
hogehogefugafuga

商品管理テーブル

| colomn_name | 説明 | type |
| -- | -- | -- |
| name | 商品の名前 | string(max:8) |
| increase_amount | 在庫の増加する数量 | number(min:0,defaoult:1) |
| decrease_amount | 在庫の現象する数量 | number(min:0,default:1) |
| price | 売値 | number(min:0,default:0) |

