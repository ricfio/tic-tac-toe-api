#!/bin/bash

GIT_FOLDER=`git rev-parse --git-dir`/hooks
SRC_FOLDER=./scripts/git-hooks

function print_usage()
{
	echo -e "Usage:  $(basename $0) [COMMAND]" 
	echo -e ""
	echo -e "Commands:"
	echo -e "  install            Install ${GIT_FOLDER}/precommit"
	echo -e "  uninstall          Uninstall ${GIT_FOLDER}/pre-commit"
	echo -e "  run                Run ${SRC_FOLDER}/pre-commit.sh"
	echo -e ""
}

function do_install()
{
    echo "cp $SRC_FOLDER/pre-commit.sh $GIT_FOLDER/pre-commit"
    cp $SRC_FOLDER/pre-commit.sh $GIT_FOLDER/pre-commit
    exit 0
}

function do_uninstall()
{
    echo "rm $GIT_FOLDER/pre-commit"
    rm $GIT_FOLDER/pre-commit
    exit 0
}

function do_run()
{
    $SRC_FOLDER/pre-commit.sh
    exit 0
}

# Parsing dei parametri ricevuti sulla stringa di comando
for i in "$@"; do
	case "$i" in
		install)
			do_install ${@/$i}
			;; 
		uninstall)
			do_uninstall ${@/$i}
			;; 
		run)
			do_run ${@/$i}
			;;
	esac
done
print_usage
exit 0
