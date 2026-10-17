# Pouët data

Weekly public JSON dumps of (part of) the [Pouët.net](https://www.pouet.net)
database, on <https://data.pouet.net>. `json.php` lists them as a JSON feed.

## How it works

This repo only lists the dumps. Every Wednesday, www's
`cron.php createDataDump` writes them into `dumps/` (gitignored; www's
`POUET_DATADUMP_PATH` points at this checkout), and a cron job in
pouet-infra recompresses them from gzip to xz.

## Running it locally

The local stack (Docker images matching production, configs and sample
data) lives in [pouet-infra](https://github.com/pouetnet/pouet-infra),
which is only open to the team for now. If you have access:

```sh
git clone git@github.com:pouetnet/pouet-infra.git
cd pouet-infra
just setup
```

This clones this repo and the other site repos under `sites/`, starts
everything and loads sample data. Data is then on <http://localhost:8084>,
with no dumps to list until you make some with
`just cron createDataDump`. pouet-infra's
`ARCHITECTURE.md` describes how it runs in production.

## Contributing

Open a pull request against `master`. PRs are squash-merged once CI
passes, and a merged PR is live on data.pouet.net within a minute.

PHP follows [PER Coding Style 3.0](https://www.php-fig.org/per/coding-style/).
CI flags style issues without blocking the merge; `php-cs-fixer fix`
(3.95.27) fixes them.
