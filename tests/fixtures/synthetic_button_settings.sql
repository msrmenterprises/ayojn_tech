INSERT INTO buttons_setting (
    page_id,
    button_id,
    button_icon,
    button_name,
    button_hover,
    class_name
)
SELECT pages.page_id, numbers.button_id, 'fa fa-check',
       'Synthetic test action', 'Synthetic test action', 'synthetic-test-button'
FROM (
    SELECT 'home_page' AS page_id, 1 AS button_count
    UNION ALL SELECT 'header', 3
    UNION ALL SELECT 'notification', 1
    UNION ALL SELECT 'offer_markerplace', 5
    UNION ALL SELECT 'beneficiary_edit', 2
    UNION ALL SELECT 'directpay', 2
    UNION ALL SELECT 'directpay_add', 2
    UNION ALL SELECT 'beneficiary_list', 4
    UNION ALL SELECT 'Partner', 6
    UNION ALL SELECT 'offer_bid', 11
    UNION ALL SELECT 'recieve_collaboration', 5
    UNION ALL SELECT 'unpaid_bid', 1
    UNION ALL SELECT 'Partner_offer_details', 1
    UNION ALL SELECT 'offer_opportunity', 8
    UNION ALL SELECT 'recieve_my_bid', 2
    UNION ALL SELECT 'offer_bid_response', 4
    UNION ALL SELECT 'recieve_build_opportunity', 1
    UNION ALL SELECT 'recieve_opportunity', 7
    UNION ALL SELECT 'recieve_bid', 13
    UNION ALL SELECT 'recommend', 2
) AS pages
JOIN (
    SELECT 1 AS button_id
    UNION ALL SELECT 2
    UNION ALL SELECT 3
    UNION ALL SELECT 4
    UNION ALL SELECT 5
    UNION ALL SELECT 6
    UNION ALL SELECT 7
    UNION ALL SELECT 8
    UNION ALL SELECT 9
    UNION ALL SELECT 10
    UNION ALL SELECT 11
    UNION ALL SELECT 12
    UNION ALL SELECT 13
) AS numbers ON numbers.button_id <= pages.button_count
WHERE LOCATE('_test_', DATABASE()) > 0
  AND NOT EXISTS (
      SELECT 1
      FROM buttons_setting existing
      WHERE existing.page_id = pages.page_id
        AND existing.button_id = numbers.button_id
  );
