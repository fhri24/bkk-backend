# GitHub Issue Templates - Summary

## What I Created

I've set up GitHub issue templates for your repository. These templates will automatically appear when someone clicks "New Issue" on GitHub.

## Files Created

### Issue Templates
1. **Bug Report** (`.github/ISSUE_TEMPLATE/bug_report.md`)
   - For reporting bugs and unexpected behavior
   - Includes environment details, steps to reproduce, error logs
   - Label: `bug`

2. **Feature Request** (`.github/ISSUE_TEMPLATE/feature_request.md`)
   - For suggesting new features or enhancements
   - Includes problem description and proposed solution
   - Label: `enhancement`

3. **Docker/Podman Issue** (`.github/ISSUE_TEMPLATE/docker_issue.md`)
   - Specifically for Docker/Podman related issues
   - Includes container logs, version info, commands
   - Labels: `docker`, `help wanted`

4. **Documentation Issue** (`.github/ISSUE_TEMPLATE/documentation.md`)
   - For reporting unclear or incorrect documentation
   - Label: `documentation`

5. **Question** (`.github/ISSUE_TEMPLATE/question.md`)
   - For asking questions about the project
   - Label: `question`

### Configuration
6. **Issue Template Config** (`.github/ISSUE_TEMPLATE/config.yml`)
   - Enables blank issues
   - Adds link to Security Advisories for vulnerability reports

### Pull Request Template
7. **Pull Request Template** (`.github/PULL_REQUEST_TEMPLATE.md`)
   - Automatically appears when someone creates a PR
   - Includes checklist for code quality

### Contributing Guide
8. **CONTRIBUTING.md**
   - Guidelines for contributors
   - Development setup instructions
   - Code style guidelines
   - Commit message format

## How It Works

### For Issue Creators
When someone clicks "New Issue" on GitHub:
1. They see a list of issue types (Bug Report, Feature Request, etc.)
2. They select the appropriate type
3. The template pre-fills with sections to complete
4. They fill in the details and submit

### No Workflow Required
Issue templates don't need GitHub Actions workflows - they're just markdown files that GitHub automatically uses.

## Next Steps

1. **Commit and Push**:
   ```bash
   git add .github/ CONTRIBUTING.md
   git commit -m "Add issue templates and contributing guidelines"
   git push origin main
   ```

2. **Test on GitHub**:
   - Go to your repository on GitHub
   - Click "Issues" → "New Issue"
   - You'll see the templates!

3. **Check Current Issues**:
   Visit: https://github.com/fauzymadani/bkk-backend/issues

## Optional: Add Labels

To make the templates work better, create these labels on GitHub:
- `bug` (red)
- `enhancement` (blue)
- `docker` (purple)
- `documentation` (yellow)
- `question` (pink)
- `help wanted` (green)

GitHub will auto-create some of these, but you can customize them at:
https://github.com/fauzymadani/bkk-backend/labels

## Benefits

- **Structured Issues**: Contributors provide all necessary information
- **Faster Triage**: Issues are pre-categorized with labels
- **Better Quality**: Templates guide users to include relevant details
- **Consistency**: All issues follow the same format
- **Professional**: Shows your project is well-maintained
