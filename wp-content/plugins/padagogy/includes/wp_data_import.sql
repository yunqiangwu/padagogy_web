SET AUTOCOMMIT = 0;

-- 修改结束符，防止在mysql命令行中默认分号直接运行
DELIMITER $$

-- 创建一个计算拆分后字符串的个数函数
DROP FUNCTION IF EXISTS calc_length $$
CREATE FUNCTION calc_length(str VARCHAR(200), splitstr VARCHAR(5))
  RETURNS INT(11)

  BEGIN
    SET str = trim(str);
    IF str IS NULL OR str = ''
    THEN
      RETURN 0;
    END IF;
    RETURN length(str) - length(replace(str, splitstr, '')) + 1;
  END $$

-- 创建一个模拟的split拆分字符串的函数
DROP FUNCTION IF EXISTS split_string $$
CREATE FUNCTION split_string(str VARCHAR(200), splitstr VARCHAR(5), strindex INT)
  RETURNS VARCHAR(255)
  BEGIN
    DECLARE result VARCHAR(255) DEFAULT '';
    SET str = trim(str);
    SET result = reverse(substring_index(reverse(substring_index(str, splitstr, strindex)), splitstr, 1));
    RETURN result;
  END $$


DELIMITER $$

DROP FUNCTION IF EXISTS `url_decode` $$
CREATE DEFINER =`root`@`%` FUNCTION `url_decode`(original_text TEXT)
  RETURNS TEXT CHARSET utf8
  BEGIN
    DECLARE new_text TEXT DEFAULT NULL;
    DECLARE pointer INT DEFAULT 1;
    DECLARE end_pointer INT DEFAULT 1;
    DECLARE encoded_text TEXT DEFAULT NULL;
    DECLARE result_text TEXT DEFAULT NULL;

    SET new_text = REPLACE(original_text, '+', ' ');
    SET new_text = REPLACE(new_text, '%0A', '\r\n');

    SET pointer = LOCATE("%", new_text);
    WHILE pointer <> 0 && pointer < (CHAR_LENGTH(new_text) - 2) DO
      SET end_pointer = pointer + 3;
      WHILE MID(new_text, end_pointer, 1) = "%" DO
        SET end_pointer = end_pointer + 3;
      END WHILE;

      SET encoded_text = MID(new_text, pointer, end_pointer - pointer);
      SET result_text = CONVERT(UNHEX(REPLACE(encoded_text , "%", "")) USING utf8);
      SET new_text = REPLACE(new_text, encoded_text, result_text);
      SET pointer = LOCATE("%", new_text, pointer + CHAR_LENGTH(result_text));
    END WHILE;

    RETURN new_text;

  END $$

DELIMITER ;





DROP PROCEDURE
IF EXISTS p_import_comment;

CREATE PROCEDURE p_import_comment(var_post_id INTEGER, p_jpw_id VARCHAR(10))
  BEGIN
    DECLARE _last_insert_id INTEGER DEFAULT NULL;
    DECLARE done INT DEFAULT 0;

    declare  row_ctime bigint(40) DEFAULT NULL;
    declare  row_from_text varchar(255) DEFAULT NULL;
    declare  row_user_name varchar(255) DEFAULT NULL;
    declare  row_cmtcnt longtext;
    declare  row_rating int(2) DEFAULT NULL;
    declare  row_vote_up int(20) DEFAULT NULL;
    declare  row_vote_down int(20) DEFAULT NULL;
    declare  row_praise int(20) DEFAULT NULL;
    declare  row_ip varchar(255) DEFAULT NULL;
    declare  row_isrecommend tinyint(2) DEFAULT NULL;
    declare  row_user_icon varchar(255) DEFAULT NULL;

    DECLARE padagogy_comment_cur CURSOR FOR SELECT
                                      `ctime`,
                                      `from_text`,
                                      `user_name`,
                                      `cmtcnt`,
                                      `rating`,
                                      `vote_up`,
                                      `vote_down`,
                                      `praise`,
                                      `ip`,
                                      `isrecommend`,
                                      `user_icon`
                                    FROM
                                      padagogy_comments
                                    WHERE jpw_id = p_jpw_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN padagogy_comment_cur;

    REPEAT


      FETCH padagogy_comment_cur
      INTO row_ctime,
        row_from_text,
        row_user_name,
        row_cmtcnt,
        row_rating,
        row_vote_up,
        row_vote_down,
        row_praise,
        row_ip,
        row_isrecommend,
        row_user_icon;


      IF NOT done THEN

        INSERT INTO `wp_comments` ( `comment_post_ID`, `comment_author`, `comment_author_email`, `comment_author_url`,
                                   `comment_author_IP`, `comment_date`, `comment_date_gmt`, `comment_content`, `comment_karma`, `comment_approved`,
                                   `comment_agent`, `comment_type`, `comment_parent`, `user_id`, `comment_mail_notify`)
        VALUES ( var_post_id, row_user_name, '842269151@qq.com', '', row_ip, IFNULL(FROM_UNIXTIME(SUBSTR(row_ctime,1,10)),now()), IFNULL(FROM_UNIXTIME(SUBSTR(row_ctime,1,10)),now()),
                              IFNULL(url_decode( row_cmtcnt),''), '0', '1',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                '', '0', '0', '1');
        SET  _last_insert_id = LAST_INSERT_ID();
        INSERT INTO `wp_commentmeta` ( `comment_id`, `meta_key`, `meta_value`) VALUES
          (_last_insert_id, 'rating', row_rating),
          (_last_insert_id, 'praise', row_praise),
          (_last_insert_id, 'user_icon', row_user_icon),
          (_last_insert_id, 'isrecommend', row_isrecommend),
          (_last_insert_id, 'vote_up', row_vote_up),
          (_last_insert_id, 'vote_down', FLOOR( row_vote_down * RAND())),
          (_last_insert_id, 'from', row_from_text);


END IF;


    UNTIL  done
    END   REPEAT;
     CLOSE  padagogy_comment_cur;
    end;


DROP PROCEDURE
IF EXISTS wk;

CREATE PROCEDURE wk()
  BEGIN

    DECLARE cnt INT DEFAULT 0;
    DECLARE i INT DEFAULT 0;
    DECLARE is_exist_term INT DEFAULT 0;
    DECLARE p_term_id INT DEFAULT 0;
    DECLARE p_term_taxonomy_id INT DEFAULT 0;
    DECLARE str VARCHAR(2000) DEFAULT '';
    DECLARE _last_insert_id INTEGER DEFAULT NULL;
    DECLARE done INT DEFAULT 0;
    DECLARE row_p_jpw_id INTEGER;
    DECLARE row_p_app_name VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_app_name_temp VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_dl_url VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_icon_url VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_file_size VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_app_score VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_type_label VARCHAR(512) DEFAULT NULL;
    DECLARE row_p_produce_company VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_last_release_time VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_dl_count VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_other_msg VARCHAR(255) DEFAULT NULL;
    DECLARE row_p_app_desc LONGTEXT;
    DECLARE row_p_app_img VARCHAR(2048) DEFAULT NULL;
    DECLARE row_p_vote_up INT(20) DEFAULT NULL;
    DECLARE row_p_vote_down INT(20) DEFAULT NULL;
    DECLARE var_i INT DEFAULT 1;
    DECLARE padagogy_cur CURSOR FOR SELECT
                                      `jpw_id`,
                                      `app_name`,
                                      `dl_url`,
                                      `icon_url`,
                                      `file_size`,
                                      `app_score`,
                                      `type_label`,
                                      `produce_company`,
                                      `last_release_time`,
                                      `dl_count`,
                                      `other_msg`,
                                      `app_desc`,
                                      `app_img`,
                                      `vote_up`,
                                      `vote_down`
                                    FROM
                                      padagogy
#                                     LIMIT 0,
#                                       4
    ;


    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN padagogy_cur;

    START TRANSACTION;


    REPEAT
      FETCH padagogy_cur
      INTO row_p_jpw_id,
        row_p_app_name,
        row_p_dl_url,
        row_p_icon_url,
        row_p_file_size,
        row_p_app_score,
        row_p_type_label,
        row_p_produce_company,
        row_p_last_release_time,
        row_p_dl_count,
        row_p_other_msg,
        row_p_app_desc,
        row_p_app_img,
        row_p_vote_up,
        row_p_vote_down;


      IF NOT done
      THEN

        SET row_p_app_name_temp = concat(
            'p_',
            REPLACE(
                to_base64(row_p_app_name),
                '=',
                ''
            )
        );

        SET row_p_app_name_temp = replace(row_p_app_name_temp, '+', '');
        SET row_p_app_name_temp = replace(row_p_app_name_temp, '/', '');

        INSERT INTO `wp_posts` (
          `post_author`,
          `post_date`,
          `post_date_gmt`,
          `post_content`,
          `post_title`,
          `post_excerpt`,
          `post_status`,
          `comment_status`,
          `ping_status`,
          `post_password`,
          `post_name`,
          `to_ping`,
          `pinged`,
          `post_modified`,
          `post_modified_gmt`,
          `post_content_filtered`,
          `post_parent`,
          `guid`,
          `menu_order`,
          `post_type`,
          `post_mime_type`,
          `comment_count`
        )
        VALUES
          (
            '1',
            now(),
            CONVERT_TZ(NOW(), '-08:00', '+00:00'),
            IFNULL(url_decode( row_p_app_desc),''),
            row_p_app_name,
            '',
            'publish',
            'open',
            'closed',
            '',
            row_p_app_name_temp,
            '',
            '',
            now(),
            CONVERT_TZ(NOW(), '-08:00', '+00:00'),
            '',
            '0',
            concat(
                'http://127.0.0.1/padagogy/',
                row_p_app_name_temp,
                '/'
            ),
            '0',
            'padagogy',
            '',
            '0'
          );

        SET _last_insert_id = LAST_INSERT_ID();

        INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES
          (_last_insert_id, 'app_icon', row_p_icon_url),
          (_last_insert_id, 'dl_url', row_p_dl_url),
          (_last_insert_id, 'dl_count', row_p_dl_count),
          (_last_insert_id, 'app_img', row_p_app_img),
          (_last_insert_id, 'last_release_time', row_p_last_release_time),
          (_last_insert_id, 'app_score', row_p_app_score),
          (_last_insert_id, 'produce_company', row_p_produce_company),
          (_last_insert_id, 'other_msg', row_p_other_msg),
          (_last_insert_id, 'file_size', row_p_file_size),
          (_last_insert_id, 'data_from', 'jpw_spider'),
          (_last_insert_id, 'jpw_id', row_p_jpw_id),
          (_last_insert_id, 'vote_up', row_p_vote_up),
          (_last_insert_id, 'vote_down', row_p_vote_down);
        #         select _last_insert_id,last_insert_id();

        # 處理分類标签
        SET cnt = calc_length(row_p_type_label, ' ');
        #         select cnt,row_p_type_label;
        SET i = 0;
        IF (cnt > 0)
        THEN
          WHILE i < cnt
          DO
            SET i = i + 1;
            SET str = split_string(row_p_type_label, ' ', i);
            SELECT count(term_id)
            INTO is_exist_term
            FROM wp_terms
            WHERE name = str;
            IF is_exist_term
            THEN
              SELECT term_id
              INTO p_term_id
              FROM wp_terms
              WHERE name = str;
              select term_taxonomy_id into p_term_taxonomy_id from wp_term_taxonomy where term_id = p_term_id and taxonomy = 'app_classification' ;
            ELSE
              INSERT INTO wp_terms(`name`, `slug`, `term_group`)
              VALUES(str, concat('p_', REPLACE(REPLACE(REPLACE(to_base64(str),'/',''), '=', ''),'+','')), '0');
              SET p_term_id = last_insert_id();
              INSERT INTO `wp_term_taxonomy` (`term_id`, `taxonomy`, `description`, `parent`, `count`)
              VALUES (p_term_id , 'app_classification', '', '0', '1');
              set p_term_taxonomy_id = LAST_INSERT_ID();
            END IF;

            INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (_last_insert_id, p_term_taxonomy_id, '0');
            UPDATE `wp_term_taxonomy` SET count=count + 1 WHERE (`term_taxonomy_id`=p_term_taxonomy_id);
          END WHILE;

        END IF;

        # 处理评论内容
        call p_import_comment(_last_insert_id,row_p_jpw_id);

      END
      IF;


      SET var_i = var_i + 1;

    UNTIL  done
    END
    REPEAT;

    select var_i;

    UPDATE wp_posts posts
    SET comment_count = (
      SELECT
        count(1)
      FROM
        wp_comments comments
      WHERE
        comments.comment_post_ID = posts.ID
    );

    UPDATE wp_term_taxonomy
    set wp_term_taxonomy.count =(
      SELECT count(1)
      FROM
        wp_term_relationships
      where
        wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id

    );


    COMMIT ;

    CLOSE padagogy_cur;


  END;

CALL wk;
#  call p_import_comment(1,1559);
DROP PROCEDURE wk;
DROP PROCEDURE p_import_comment;
DROP FUNCTION IF EXISTS `url_decode`;
DROP FUNCTION calc_length;
DROP FUNCTION IF EXISTS split_string;