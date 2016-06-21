# Fishnet - development intranet branch

A branch of *Eileen Fisher's* intranet site repo for book keeping and version control.

## Frank
Currently working on:
- Searchable "tags"
- Permissions in search
- Task managaer for retail team

Progress:
- Tags are currently searchable!
	- Tags have a relevance of "1"
	- Tags are grouped by "page_id"
		- The entry with largest "relevance" is taken, rest are dropped
- searchindex (which contains the rebuild search index function) should now rebuild with needed info
	- Rebuilds with tag_id, tag_name, and access now!
- Permissions and "access" should now be considered in search!

Changes:
- SET GLOBAL max_allowed_packet=20047872 (20 MB), to allow for larger search index!

Possibilities:
- Use SQL REPLACE as well as UNION to string parse before searching
	- Might be too performance heavy... projection of roughly 6x more working
- Multi tag searching? This requires string parsing as well though...