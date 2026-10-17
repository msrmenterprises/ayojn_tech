INSERT INTO user_type (user_type, level, refer_amount)
SELECT 'Synthetic test type', 'Test', 0
WHERE LOCATE('_test_', DATABASE()) > 0
  AND NOT EXISTS (
      SELECT 1 FROM user_type WHERE user_type = 'Synthetic test type'
  );

INSERT INTO configuration (config_text, user_type, config_amt, config_type, user_earn)
SELECT settings.config_text, test_type.id, 1, 'fixed', 0
FROM (
    SELECT 'marketplace_payment' AS config_text
    UNION ALL SELECT 'hashtag_vouch'
    UNION ALL SELECT 'directpay'
    UNION ALL SELECT 'build_hashtag'
    UNION ALL SELECT 'build_hashtag_renew'
    UNION ALL SELECT 'pay_bid'
    UNION ALL SELECT 'partner_offer'
    UNION ALL SELECT 'collaboration'
    UNION ALL SELECT 'unpaid_opportunity'
    UNION ALL SELECT 'pay_opportunity '
) AS settings
JOIN (
    SELECT id
    FROM user_type
    WHERE user_type = 'Synthetic test type'
    ORDER BY id
    LIMIT 1
) AS test_type
WHERE LOCATE('_test_', DATABASE()) > 0
  AND NOT EXISTS (
      SELECT 1
      FROM configuration existing
      WHERE existing.config_text = settings.config_text
        AND existing.user_type = test_type.id
  );
