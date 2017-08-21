YII框架底层利用PDO，支持三种模式的数据库查询。

## 三种查询模式

- DAO：直接执行sql语句。 

  如 `Yii::$app->db->createCommand('SELECT * FROM post WHERE id=1')->queryOne();`

- queryBuilder：组合一个sql语句

  如`(new \yii\db\Query())->where(['film_id'=>1])->from('film')->all();`

- ActiveRecord：即ORM

## UML类图

### Connection类

- createPdoInstance() //创建pdo连接

- createCommand($sql = null, $params = []) //直接执行sql语句

  ```
  public function createCommand($sql = null, $params = [])
  {
      /** @var Command $command */
      $command = new $this->commandClass([
          'db' => $this,
          'sql' => $sql,
      ]);

      return $command->bindValues($params);
  }
  ```

  会 new 一个Command Object


### Command类

  定义了获取query结果集的方法，类似于PDOStatement类的功能。

- queryAll() //获取所以的结果集

  ```
  public function queryAll($fetchMode = null)
  {
      return $this->queryInternal('fetchAll', $fetchMode);
  }
  ```

- queryOne() //获取一行

  ```
  public function queryOne($fetchMode = null)
  {
      return $this->queryInternal('fetch', $fetchMode);
  }
  ```

- queryColumn()  // Executes the SQL statement and returns the first column of the result.

  ```
  public function queryColumn()
  {
      return $this->queryInternal('fetchAll', \PDO::FETCH_COLUMN);
  }
  ```

  queryInternal()方法去调用PDO的fetchAll等方法获取结果集。

### Query类

- $select
- $where
- $join
  ... 等sql属性
- where()
  ```
  public function where($condition)
  {
      $this->where = $condition;
      return $this;
  }
  ```

  ​... 以及limit()、offset()、orderBy()等链式调用方法，用来给前面的sql属性字段赋值。

- all() 获取sql执行的结果集

  ```
  public function all($db = null)
  {
      if ($this->emulateExecution) {
          return [];
      }
      $rows = $this->createCommand($db)->queryAll();
      return $this->populate($rows);
  }
  ```

  接下来，有几个重要的动作：

  1. QueryBuilder：根据不同数据库如mysql、oracle的sql语法，拼接sql
  2. 既然sql以及拼接完成了，就可以重用前面的Connection类中的createCommand()方法执行sql语句。

  下面接着看createCommand()方法

- createCommand()

  ```
  public function createCommand($db = null)
  {
      if ($db === null) {
          $db = Yii::$app->getDb();
      }
      list ($sql, $params) = $db->getQueryBuilder()->build($this);

      return $db->createCommand($sql, $params);
  }
  ```

  ```
  public function getQueryBuilder()
  {
  	return $this->getSchema()->getQueryBuilder();
  }
  ```

  以MYSQL为例，getSchema()->getQueryBuilder() 我们可以猜测是获得了yii\db\mysql\QueryBuilder对象，接下来看QueryBuilder类

### QueryBuilder类

​	首先这里是个标准的继承结构

```
 class yii\db\mysql\QueryBuilder extends \yii\db\QueryBuilder{
      ...
  }
  class yii\db\sqlite\QueryBuilder extends \yii\db\QueryBuilder{
      ...
  }
```

​	QueryBuilder做的工作就是把Query()类中链式调用赋值的sql属性，拼接成sql语句。

​	QueryBuilder类的核心就是build()方法

- build()

  ```
  $clauses = [
      $this->buildSelect($query->select, $params, $query->distinct, $query->selectOption),
      $this->buildFrom($query->from, $params),
      $this->buildJoin($query->join, $params),
      $this->buildWhere($query->where, $params),
      $this->buildGroupBy($query->groupBy),
      $this->buildHaving($query->having, $params),
  ];

  $sql = implode($this->separator, array_filter($clauses));
  $sql = $this->buildOrderByAndLimit($sql, $query->orderBy, $query->limit, $query->offset);
  ```

  逻辑很清楚，sql的各个部件，implode到一起。

