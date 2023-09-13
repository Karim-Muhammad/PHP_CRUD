### Difference between `PRIMARY`, `UNIQUE` and `INDEX` in MySQL?
> all used to improve data integrity, query performance, and data retrieval speed
- `PRIMARY`
  - A table can have only one `PRIMARY` key
  - A `PRIMARY` key column cannot have `NULL` values
  - By default, `PRIMARY` key is clustered index and data in the database table is physically organized in the sequence of `PRIMARY` key values
  - `Primary` keys are also used as a basis for establishing relationships between tables (foreign keys).
  - `PRIMARY` key is a combination of `UNIQUE`(thus INDEX) and `NOT NULL`
- `UNIQUE`
  - A table can have more than one `UNIQUE` column (unlike `PRIMARY` key which used once per table)
  - A `UNIQUE` column can have `NULL` values (unlike `PRIMARY` key which cannot have `NULL` values)
  - By default, `UNIQUE` creates a `NONCLUSTERED` index
  - `UNIQUE` constraint is used to prevent the duplication of key values within the rows of a table
- `INDEX`
  - A table can have more than one `INDEX` column
  - An `INDEX` column can have `NULL` values

Clustered Index:
    - A clustered index determines the physical order of data in a table.
    - When a table has a clustered index, the rows are stored on disk in the same order as the index. In other words, the rows themselves are organized based on the clustered index key.
    - A table can have only one clustered index because the actual data order can't be changed multiple times.
    - The `primary key` constraint automatically creates a `clustered index` if one doesn't already exist on the specified column(s).

Non-Clustered Index:
    - A non-clustered index is a separate data structure that contains a copy of the indexed columns along with a pointer to the actual data rows.
    - Non-clustered indexes don't affect the physical order of data in the table; they provide a separate way to access the data efficiently.
    - A table can have multiple non-clustered indexes.


When creating indexes for different purposes:
    - `Primary Key` Constraint
        - If you define a primary key constraint, the underlying index can be either clustered or non-clustered, depending on your database system and configuration. In SQL Server, for instance, the primary key constraint creates a clustered index by default, but this can be changed to non-clustered if desired.
    - `Unique` Constraint
        - When you define a unique constraint, the underlying index can also be either clustered or non-clustered, depending on your choice and the database system's defaults.
    - `Index`
        - for Performance Optimization: When you create an index specifically to improve data retrieval performance, you can choose between creating a non-clustered index or a clustered index, depending on the desired impact on query performance and storage considerations.

_Summary_
It's important to note that choosing whether to create a clustered or non-clustered index depends on the specific requirements of your application and the types of queries you frequently run. Clustered indexes are best suited for columns that are often used in range queries or when you want to retrieve entire rows in a particular order. Non-clustered indexes are more versatile and can be created on columns that are used in various types of queries.



ITI Notes:
database: a collection of tables
data of table is stored in different places in the hard disk and all buffers gathered together
and when we search on some rows, we search on these separated files

but when we search with index field, there is a table created for this index field and location of original row that in the original table
| index | row location |
|-------| ------------ |
| 1     | 0x0001       | (location of row 1 in the original table)

so when we search on `row 1`, instead of search on all separated files, we search on this table which is ordered and get location of row 1 fastly
> when we create `index`, `unique`, `primary` there is a table created for each one of them, and this table is ordered and has location of original row in the original table


so why we not use index for all fields?
because when we insert new row, we need to insert it in the original table and in the index table, and sorting operation will start again and this is not good for performance
so we use index for fields that we search on them frequently

so index, unique, primary helped us in searching, but it will give us bad performance in inserting, updating and deleting
because in these operations, we need to update the original table and the index table and do sorting operation on index table to be ordered



Big Example

if we have table `students` with fields `id`, `name`, `age`, `gpa`, `address`, `phone`, `email`, `gender`
and we made id as `primary key`, and name as `unique`, age as a `unique`, `email` as a `unique`

when we search with these fields, we get results fastly because there is a table for each one of them and it's ordered and has location of original row in the original table

but when we insert new student for example, we will insert it in original table, and in the table of `primary` and `unique` fields, then sorting operation will start in all these tables, means 4 tables will need to be sorted, and this is not good for performance


each digit in NUMBER has 4 bytes
so if we have NUMBER(10) it will take 40 bytes

each character in VARCHAR has 1 byte
so if we have VARCHAR(10) it will take 10 bytes

Datatypes in SQL and their sizes:


#### Deleting Rows
when we delete some rows, next new row will have id of the last deleted row + 1

if we have 4 rows, then i deleted three rows, then I insert new row, this new row will have id `4` not `2`
mysql doesn't delete the row, it just make it invisible, and when we insert new row, it will take the id of the last deleted row
we can recover the deleted rows by using `rollback` command

there is another reason why sql continue id value with 4 not 2, in this case we don't need to loop through all rows in table to get the last id, we just get the last id and add 1 to it

#### Difference between Delete and Truncate

`Delete` command is used to delete some rows from table, and we can use `where` to specify which rows we want to delete (just put [x] in the row you want to delete - make it invisible)
`Truncate` command is used to __delete all__ rows from table, and we can't use `where` with it, We cannot `roll back` the data after using the TRUNCATE command. 
because it will remove all Index file and empty table completely, thus `Truncate` is faster than Delete 


#### Uniquness
Database validate our data to be unique, but it cannot validat emails, password, phone numbers, etc.. to be correct
so we need in our interface (web page form) to validate these fields to be correct


#### Foreign Key

Types of Actions
1. `CASCADE`: When we delete a row from the parent table, the corresponding rows in the child table are automatically deleted. Similarly, when we update a row in the parent table, the corresponding rows in the child table are automatically updated.
2. `SET NULL`: When we delete a row from the parent table, the corresponding rows in the child table are set to NULL. Similarly, when we update a row in the parent table, the corresponding rows in the child table are set to NULL. (unknown author for example)
3. `RESTRICT`: The RESTRICT action means that the deletion or update of a row in the parent table is not allowed if there is a related row in the child table. In this case, you need to either delete the child row first, or set the value of the foreign key to NULL.
4. `NO ACTION`: The NO ACTION means that the deletion or update of a row in the parent table is allowed and related row in the child table still keep its data without removing any value.

but `NO ACTION` will may cause Errors, because when we delete an author for example, post will keep author_id although this author is deleted.
in case `SET NULL` author_id will be null, and in case `CASCADE` post will be deleted