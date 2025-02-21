build::
	docker build -f docker/Dockerfile -t commission-computer .
	@docker run --rm -it \
		--user `id -u`:`id -g` \
		--volume "`pwd`:/app" -w /app \
		commission-computer \
		composer install

test::
	docker run --rm \
		--user `id -u`:`id -g` \
		--volume "`pwd`:/app" -w /app \
		commission-computer \
		vendor/bin/phpunit

cli::
	@docker run --rm -it \
		--user `id -u`:`id -g` \
		--volume "`pwd`:/app" -w /app \
		commission-computer \
		bash

compute::
	docker run --rm \
		--user `id -u`:`id -g` \
		--volume "`pwd`:/app" -w /app \
		commission-computer \
		php app.php