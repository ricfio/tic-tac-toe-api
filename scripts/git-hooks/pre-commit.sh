#!/bin/bash

EXIT_CODE=0
COLOR_NONE='\033[0m'    # NO COLOR
COLOR_MAIN='\033[1;37m' # WHITE
COLOR_FAIL='\033[0;31m' # RED
COLOR_SUCC='\033[0;32m' # GREEN

function print_section()
{
    # set -f
    echo
    echo -e " $COLOR_MAIN $@ $COLOR_NONE"
    # set +f
}

function run_check()
{
    local name=${@:1:1}
    local command=${@:2}
	# set -f
	# echo $command
	# set +f
	$command > /dev/null 2>&1
    local rc=$?
    if [ $rc -eq 0 ]; then
        # echo -e "    $COLOR_SUCC✔$COLOR_NONE $name ($command)"
        echo -e "    $COLOR_SUCC✔$COLOR_NONE $name"
    else
        # echo -e "    $COLOR_FAIL✘$COLOR_NONE $name ($command => $rc)"
        echo -e "    $COLOR_FAIL✘$COLOR_NONE $name"
        if [ $EXIT_CODE -eq 0 ]; then
            EXIT_CODE=$rc
        fi
    fi    
}

# echo "Running pre-commit hook..."

print_section "Coding Standard"
run_check "PHP CS Fixer" make php-cs-fixer
#run_check "PHP Mess Detector" make phpmd

print_section "Static Analysis"
run_check "Psalm       " make psalm
#run_check "PHPStan     " make phpstan
#run_check "PHPArkitect " make phparkitect

print_section "Test Execution"
run_check "PHPUnit     " make test

echo
if [ $EXIT_CODE -ne 0 ]; then
    echo "exit with code $EXIT_CODE"
    exit $EXIT_CODE
fi
