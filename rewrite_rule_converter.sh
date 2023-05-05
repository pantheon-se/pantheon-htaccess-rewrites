#!/bin/bash
#Made By Vudubond @https://github.com/Vudubond
#Usage Example: ./rewrite_rule_converter.sh /path/to/.htaccess /path/to/phprules.txt

# set the input file variable

if [ $# -ne 2 ]; then
  echo "Usage: $0 input_file output_file"
  exit 1
fi

# set the input file and output file variables
input_file="$1"
output_file="$2"

# loop through each line in the input file
while read -r line; do
  # get the old path and new path from the line
  old_path=$(echo "$line" | awk '{print $3}')
  new_path=$(echo "$line" | awk '{print $NF}')

  # print the converted line in the desired syntax
  echo "'$old_path' => '$new_path'," >> "$output_file"
done < "$input_file"
