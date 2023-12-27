# 🤗 Contributing to GraphenePHP

> **Note:** Alternatively you can check [Github Official Docs](https://docs.github.com/en/pull-requests) for clear steps 

## 🍴 1. Fork the Repository

- Visit the GitHub repository you want to contribute to.
- Click the "Fork" button in the top right corner of the repository's page.
- This creates a copy (fork) of the repository in your GitHub account.

## 📋 2. Clone Your Fork

Open your terminal or Git Bash.

Clone your forked repository to your local machine

```bash
git clone https://github.com/<your-username>/GraphenePHP.git
```

## 📂 3. Navigate to the Cloned Repository

- Change into the directory of the cloned repository


```bash
cd repository
```

## 🌲 4. Create a New Branch

- Create a new branch for your changes. This helps keep your changes isolated:

```bash
git checkout -b feature-branch
```

## 📝 5. Make Changes

- Make the necessary changes to the files in your local repository using your preferred text editor or IDE.

## 💬 6. Commit Changes

- Stage the changes

```bash
git add .
```
- Commit the changes

```bash
git commit -m "Add a meaningful commit message"
```

## ⬆️ 7. Push Changes

- Push the changes to your fork on GitHub:

```bash
git push origin feature-branch
```

## 📩 8. Create a Pull Request

- Open your browser and go to your fork on GitHub.
- Switch to the branch you created (feature-branch).
- Click on the "New Pull Request" button.
- Set the base repository and branch to the original repository and branch.
- Set the head repository and branch to your fork and branch.
- Click "Create Pull Request."

## ⌛ 9. Wait for Review

- Repository maintainers will review your changes, ask for any necessary modifications, and eventually merge the pull request.

## 🌚 10. Keep Your Fork Updated

- Periodically, you may want to sync your fork with the original repository to incorporate any changes made by others:

```bash
git fetch upstream
git checkout main
git merge upstream/main
git push origin main
```
