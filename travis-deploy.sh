#!/usr/bin/env bash

# 1. Clone complete SVN repository to separate directory
svn co https://plugins.svn.wordpress.org/$PLUGIN_SLUG ../svn

# 2. Copy git repository contents to SNV trunk/ directory
rm -rfv ../svn/trunk/*
cp -fR ./* ../svn/trunk/

# 3. Switch to SVN repository
cd ../svn/trunk/

# 4. Move assets/ to SVN /assets/
rm -rfv ../assets/*
mv ./wporg_assets/* ../assets/
rm -rfv ./wporg_assets/

# 5. Clean up unnecessary files
rm -rf .git/
rm travis-deploy.sh

# 6. Go to SVN repository root
cd ../

# 7. Create SVN tag
if [ -d "tags/$TRAVIS_TAG" ]; then rm -Rf tags/$TRAVIS_TAG; fi
cp -fR trunk tags/$TRAVIS_TAG

# 8. Push SVN tag
svn add --force * --auto-props --parents --depth infinity -q
svn status | grep '^\!' | sed 's/! *//' | xargs -I% svn rm %
svn ci --non-interactive --no-auth-cache -m "Release $TRAVIS_TAG" --username $SVN_USERNAME --password $SVN_PASSWORD