SELECT T1.storage_id, T1.category_id, T2.quantity
FROM (
    SELECT p.storage_id, p.category_id, MAX(p.time) time
    FROM product p
    GROUP BY p.storage_id, p.category_id
) T1
LEFT JOIN product T2
ON T1.storage_id = T2.storage_id
    AND T1.category_id = T2.category_id
	 AND T1.time = T2.time
ORDER BY T1.storage_id, T1.category_id
