# test_12_12_2022
 Тестовое

------------------------------------------------------------------

Задание https://github.com/rreeggeenntt4/test_12_12_2022/raw/main/target/Тестовое%20задание.docx

------------------------------------------------------------------

Ход выполнения:
1) index1.php

2) index2.php - для теста запросов заполнит базу данных
2.1) 
SELECT DISTINCT users.id, users.name 
FROM users 
INNER JOIN `orders` ON users.id = orders.users_id
WHERE orders.status = 0;

2.2)
SELECT DISTINCT users.id, users.name 
FROM users 
INNER JOIN `orders` ON users.id = orders.users_id
WHERE orders.status = 1
GROUP BY orders.users_id HAVING COUNT(orders.status) > 5;

3) index3.php

4) index4.php

<img src="https://github.com/rreeggeenntt4/apitest.ru/blob/main/media/2022-12-07_21-01-06.png" alt="">



5) index5.php