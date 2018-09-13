# Moodle SQL Query Testing Helper
Are you like me and hate having to go through and replace table names and placeholders when testing SQL in Moodle code? This is a small tool, designed to be run in localhost, to help executing/testing queries written for Moodle wrappers.

***HOW TO USE***
1. Open the file on localhost.
2. Update the table name prefix if you don't wish to use 'mdl_'.
3. Paste or type the SQL from your Moodle code into the SQL input.
4. Click Convert!
5. If your query has any placeholders, fill in the inputs provided and click Convert!
6. If you stuffed up and missed filling in any placeholders, repeat step 5.
7. The query in the SQL input is ready to be copied out to run in your test environment (or whatever you planned to do).

Note: To save time, I haven't handled question marks within values, so they will be picked up and replaced with placeholders (ie only use them to represent a placeholder). Colons within values are handled though, so can be used in strings.

***EXAMPLE***

**Input**
```
SELECT *
  FROM {tablename}
 WHERE one = :placeholder1
   AND two = :secondthing
   AND four = ?
   AND three = :anotherone
   AND five = ?
```
**Output after first submit**
```
SELECT *
  FROM mdl_tablename
 WHERE one = :placeholder1
   AND two = :secondthing
   AND four = :placeholder2
   AND three = :anotherone
   AND five = :placeholder3;
```
**Output after entering placeholder values**
```
SELECT *
  FROM mdl_tablename
 WHERE one = 'values'
   AND two = 'inserted'
   AND four = 'into'
   AND three = 'the'
   AND five = 'placeholders';
```

***WARRANTY***

Software within this project is provided as is, in an effort to share scripts that have made my life easier. As such, all files are provided as is, with no warranty and no guarantee they will perform as claimed, be fit for purpose, or be compatible with any browser. Any usage of this project is at your own risk, including but not limited to data loss, manipulation or making yourself look like a fool. Any software mentioned is not affiliated with this project, and is mentioned on the basis that I have been using it. It is not a recommendation or confirmation of compatibility.
