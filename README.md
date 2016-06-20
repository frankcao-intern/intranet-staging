# Fishnet - staging intranet branch

A branch of *Eileen Fisher's* intranet site repo for intern Frank to not mess everything up!

Currently working on:
- Searchable "tags"
- Permissions in search

Progress:
- Tags are currently searchable! XD
	- Tags have a relevance of "1"
	- Tags are grouped by "page_id"
		- The entry with largest "relevance" is taken, rest are dropped
- searchindex (which contains the rebuild search index function) should now rebuild with needed info
	- Rebuilds with tag_name and access now!
- Permissions and "access" should now be considered in search!

Changes:
- SET GLOBAL max_allowed_packet=20047872, to allow for larger search index!

Possibilities:
- Use SQL REPLACE as well as UNION to string parse before searching
	- Might be too performance heavy... projection of roughly 6x more working
- Multi tag searching? This requires string parsing as well though...


DEMO SQL:

- SELECT obj_id, obj_type, section_id, section_title, page_title, page_content as revision_text,
				page_date_published, tag_name, tag_id, access,
				(IF (title_relevance = 0, content_relevance + tag_relevance, MAX((title_relevance + 0.1) + content_relevance + tag_relevance))) AS relevance
			FROM
				(SELECT *,
					(MATCH(page_title) AGAINST ("recognition" IN BOOLEAN MODE)) AS title_relevance,
					(MATCH(page_content) AGAINST ("recognition" IN BOOLEAN MODE)) AS content_relevance,
					(MATCH(tag_name) AGAINST ("recognition" in BOOLEAN MODE)) AS tag_relevance
				FROM fn_searchindex
				HAVING (title_relevance + content_relevance + content_relevance + tag_relevance) > 0) relevance
				$section_id
			GROUP BY obj_id
			ORDER BY relevance DESC, page_date_published DESC