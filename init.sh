#!/bin/bash
# 'return' when run as "source <script>" or ". <script>", 'exit' otherwise
[[ "$0" != "${BASH_SOURCE[0]}" ]] && safe_exit="return" || safe_exit="exit"

script_name=$(basename "$0")

ask_question(){
    # ask_question <question> <default>
    local ANSWER
    read -r -p "$1 ($2): " ANSWER
    echo "${ANSWER:-$2}"
}

confirm(){
    # confirm <question> (default = N)
    local ANSWER
    read -r -p "$1 (y/N): " -n 1 ANSWER
    echo " "
    [[ "$ANSWER" =~ ^[Yy]$ ]]
}

git_name=$(git config user.name)
author_name=$(ask_question "Author name" "$git_name")

git_email=$(git config user.email)
author_email=$(ask_question "Author email" "$git_email")

username_guess=${author_name//[[:blank:]]/}
author_username=$(ask_question "Author username" "$username_guess")

current_directory=$(pwd)
folder_name=$(basename "$current_directory")

package_name=$(ask_question "Package name" "$folder_name")
package_description=$(ask_question "Package description" "$package_name")

class_name=$(echo "$package_name" | sed 's/[-_]/ /g' | awk '{for(j=1;j<=NF;j++){ $j=toupper(substr($j,1,1)) substr($j,2) }}1' | sed 's/[[:space:]]//g')

class_name=$(ask_question "Class Name" "$class_name")

echo -e "Author: $author_name ($author_username, $author_email)"
echo -e "Package: $package_name <$package_description>"
echo -e "Class Name: $class_name"

package_name_underscore=`echo "-$package_name-" | tr '-' '_'`

echo
files=$(grep -E -r -l -i ":author|:package|dive|skeleton" --exclude-dir=vendor ./* | grep -v "$script_name")

echo "This script will replace the above values in all files in the project."
if ! confirm "Are you sure you wish to continue?" ; then
    $safe_exit 1
fi

echo

for file in $files ; do
    echo "Updating $file"
    temp_file="$file.temp"
    < "$file" \
      sed "s/:author_name/$author_name/g" \
    | sed "s/:author_username/$author_username/g" \
    | sed "s/:author_email/$author_email/g" \
    | sed "s/:package_name/$package_name/g" \
    | sed "s/_skeleton_/$package_name_underscore/g" \
    | sed "s/skeleton/$package_name/g" \
    | sed "s/Skeleton/$class_name/g" \
    | sed "s/:package_description/$package_description/g" \
    | sed "/^\*\*Note:\*\* Run/d" \
    > "$temp_file"
    rm -f "$file"
    new_file=`echo $file | sed -e "s/Skeleton/${class_name}/g"`
    mv "$temp_file" "$new_file"
done

prefix="laravel-"
short_package_name=${package_name#"$prefix"}
mv "./config/skeleton.php" "./config/${short_package_name}.php"
mv "./database/migrations/create_skeleton_table.php.stub" "./database/migrations/create_${short_package_name}_table.php.stub"

echo "Replaced all values, self destructing..."

rm -- "$0"
