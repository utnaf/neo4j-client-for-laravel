neo4j-seed:
	docker compose exec neo4j bash -c "cat import/seed.cypher | bin/cypher-shell -u neo4j -p neo4jSecret --format plain"

neo4j-unseed:
	docker compose exec neo4j bash -c "cat import/unseed.cypher | bin/cypher-shell -u neo4j -p neo4jSecret --format plain"
