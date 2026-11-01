# CLAUDE.md

- Don't write code comments. Name things so the code explains itself.
- Write commit messages as Conventional Commits (`type(scope): description`); see `.github/pull_request_template.md`. The release version bump is derived from them. Keep the header line to 100 characters or fewer: commitlint checks every commit in a PR, and fixing one later means rewriting history.
- Don't create scheduled tasks to check in on your work (e.g. on a PR or deploy). Finish the task in the session, or say what's left.
- Before opening a PR, scan the repo's open GitHub issues. List any the PR addresses in its description, using a closing keyword (`Closes #123`) for each one merging will fully resolve.
- When a PR's scope changes, rewrite its description to match.
