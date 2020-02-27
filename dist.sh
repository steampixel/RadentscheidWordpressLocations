
# Read in version number
read -p "Enter the new package version: "  version
echo The new version is $version

# Promt for confirmation
read -p "Is that correct? " -n 1 -r
echo    # (optional) move to a new line
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1 # handle exits from shell or function but don't exit interactive shell
fi

# Replace the version numbers
sed -i "5s/.*/* Version: $version/" sp-locations.php
sed -i "10s/.*/define('SP_LOCATIONS_VERSION', '$version');/" sp-locations.php

# Create new archive
# Move out of the plugin folder. So we can add the parent folder to the archive too
cd ..
7za a -tzip ./sp-locations/dist/sp-locations_$version.zip sp-locations/ -mx0 -xr!.git -xr!dist -xr!dist.sh
cd sp-locations

# Add the changes to git
git add sp-locations.php
git add dist

# Commit
git commit -m "Moved to version $version"
