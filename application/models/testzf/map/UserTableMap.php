<?php



/**
 * This class defines the structure of the 'users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.models.map
 */
class UserTableMap extends Dfi_Propel_Map_TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'models.map.UserTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('users');
        $this->setPhpName('User');
        $this->setClassname('User');
        $this->setPackage('models.map');
        $this->setUseIdGenerator(true);
        $this->setDescription('');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null ,' ');
        $this->addColumn('username', 'Username', 'VARCHAR', true, 50, null, false, null, null, '');
        $this->addColumn('password', 'Password', 'VARCHAR', false, 32, null, false, null, null, '');
        $this->addColumn('password_salt', 'PasswordSalt', 'VARCHAR', false, 32, null, false, null, null, '');
        $this->addColumn('real_name', 'RealName', 'VARCHAR', false, 150, null, false, null, null, '');
        $this->addColumn('role', 'Role', 'VARCHAR', false, 45, null, false, null, null, '');
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'hashable' =>  array (
),
        );
    } // getBehaviors()

} // UserTableMap
