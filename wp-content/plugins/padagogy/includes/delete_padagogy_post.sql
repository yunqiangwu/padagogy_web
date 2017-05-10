

START TRANSACTION;

drop TABLE  IF EXISTS  temp_delete_post ;
create TABLE temp_delete_post as select DISTINCT post_id FROM wp_postmeta t where t.meta_key='data_from' and t.meta_value='jpw_spider'  ;

# 删除历史版本
# select *
DELETE wp_revision
FROM wp_posts wp_revision LEFT JOIN wp_posts parent_wp ON parent_wp.ID = wp_revision.post_parent
WHERE wp_revision.post_type = 'revision' AND parent_wp.post_type = 'padagogy' AND exists(select 1 from temp_delete_post t where  t.post_id= parent_wp.ID );
#
# select * from wp_posts where post_title='sfasdf';

# 删除meta数据和标签关系数据
# select *
DELETE
  wpm, wc, wcm, wtr
FROM
  wp_posts wp LEFT JOIN wp_postmeta wpm ON wp.ID = wpm.post_id
  LEFT JOIN wp_comments wc ON wp.ID = wc.comment_post_ID
  LEFT JOIN wp_commentmeta wcm ON wc.comment_ID = wcm.comment_id
  LEFT JOIN wp_term_relationships wtr ON wp.ID = wtr.object_id
WHERE wp.post_type = 'padagogy' AND exists(select 1 from temp_delete_post t where  t.post_id= wp.ID );

# 删除文章
# select *
DELETE wp
FROM wp_posts wp
WHERE wp.post_type = 'padagogy'AND exists(select 1 from temp_delete_post t where  t.post_id= wp.ID );


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



drop TABLE  IF EXISTS  temp_delete_post ;



COMMIT ;
