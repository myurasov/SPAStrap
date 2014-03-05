#!/bin/bash

# config
serverUser=serverUser
server=$serverUser@host
serverRootDir=/serverRoot
localRootDir=/localRoot
sshOptions=
rsyncOptions="-avz --delete --exclude=.DS_Store --exclude=.git"

pushd $localRootDir

# -- Pre-commands

# increase build number
buildfile="$localRootDir/src/templates/build"
let build=1+`cat "${buildfile}"`
echo -n "$build" > "${buildfile}"
echo "# Build: ${build}"

# -- Project:

echo "# Syncing project..."

# create dirs
ssh $sshOptions -t $server "sudo mkdir -pv ${serverRootDir}; sudo chown $serverUser:$serverUser $serverRootDir"

rsync $rsyncOptions -e "ssh ${sshOptions}" ${localRootDir}/src/ $server:${serverRootDir}/src/
rsync $rsyncOptions -e "ssh ${sshOptions}" ${localRootDir}/vendor/ $server:${serverRootDir}/vendor/

# -- Post-commands

echo "# Cleaning caches..."
ssh $sshOptions -t $server "sudo ${serverRootDir}/src/cli/console app:service:create-paths --clean"

echo "# Generating Doctrine proxies"
ssh $sshOptions -t $server "sudo ${serverRootDir}/src/cli/console orm:generate-proxies"

echo "# Restarting Apache..."
ssh $sshOptions -t $server "sudo service apache2 restart"

popd