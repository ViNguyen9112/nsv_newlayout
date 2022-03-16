<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Log\Log;
/**
 * Tblmstaff Model
 *
 * @method \App\Model\Entity\Tblmstaff get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tblmstaff newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Tblmstaff[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tblmstaff|false save(EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tblmstaff saveOrFail(EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tblmstaff patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tblmstaff[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tblmstaff findOrCreate($search, callable $callback = null, $options = [])
 */
class TBLMStaffTable extends Table{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config){
        parent::initialize($config);

        $this->setTable('tblMStaff');
        $this->setDisplayField('StaffID');
        $this->setPrimaryKey('ID');

        $this->belongsTo('TBLMArea', [
            'className' => 'TBLMArea',
            'bindingKey' => 'Region',
            'foreignKey' => 'Region',
            'propertyName' => 'TBLMArea',
        ]);

        $this->belongsTo('TBLMAreaStaff', [
            'className' => 'TBLMAreaStaff',
            'bindingKey' => 'StaffID',
            'foreignKey' => 'StaffID',
            'propertyName' => 'TBLMAreaStaff',
        ]);

    }


    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator){
        $validator
            ->scalar('StaffID')
            ->maxLength('StaffID', 10)
            ->allowEmptyString('StaffID', null, 'create');

        $validator
            ->scalar('Name')
            ->maxLength('Name', 50)
            ->requirePresence('Name', 'create')
            ->notEmptyString('Name');

        $validator
            ->scalar('Password')
            ->maxLength('Password', 10)
            ->requirePresence('Password', 'create')
            ->notEmptyString('Password');

        $validator
            ->integer('Admin')
            ->notEmptyString('Admin');

        $validator
            ->dateTime('Created_at')
            ->requirePresence('Created_at', 'create')
            ->notEmptyDateTime('Created_at');

        return $validator;
    }

    /**
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findAuth(\Cake\ORM\Query $query, array $options) {
        $query
            ->select(['StaffID', 'Password', 'Position'])
            ->where([
                'FlagDelete' => 0,
            ]);
		if(!$query->first())
		{
			$logArr = [
				'sql' => $query->sql(),
				'request' => $_REQUEST,
				'server' => $_SERVER,
				'timestamp' => date('Y/m/d H:i:s')
			];
			Log::debug(json_encode($logArr, JSON_PRETTY_PRINT), ['scope' => ['login']]);
		}
		return $query;
    }

    /**
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findAdmin(\Cake\ORM\Query $query, array $options) {
        $query
            ->select(['StaffID', 'Password', 'Position'])
            ->where([
                'FlagDelete' => 0
            ]);
		return $query;
    }

    /**
     * @return array
     */
    public function getAllStaff() {
        $staff=$this->find()->select(['StaffID','Name'])->where(['FlagDelete' => 0])->order(['StaffID' => 'asc']);
        $result=[];
        foreach($staff as $key =>$value){
            $id=$value['StaffID'];
            $result[$id]=$value['StaffID'].'-'.$value['Name'];
        }
        return $result;
    }

    public function getStaffsUser($conds = []) {
        $conditions = ['FlagDelete' => 0,"Position LIKE '%Leader%'"];
        if (!empty($conds)) {
            $conditions = array_merge($conditions, $conds);
        }
        $staff=$this->find()->select(['StaffID','Name'])->where($conditions)->order(['StaffID' => 'asc']);
        $result=[];
        foreach($staff as $key =>$value){
            $id=$value['StaffID'];
            $result[$id]=$value['StaffID'].'-'.$value['Name'];
        }
        return $result;
    }

    /**
     * @return Query
     */
    public function getStaffs($conditions = [], $order = [])
    {
        return empty($conditions) ? $this->find()->select([
            'ID',
            'StaffID',
            'Name',
            'Password',
            'Position',
            'Title',
            'Region',
            'CreatedDate' => " DATE_FORMAT(Created_at, '%Y/%m/%d %H:%i:%s')",
        ]) : $this->find()->select([
            'ID',
            'StaffID',
            'TBLMStaff.Name',
            'Password',
            'Position',
            'Title',
            'TBLMStaff.Region',
            'AreaName' => "GROUP_CONCAT(TBLMArea.Name)",
            'CreatedDate' => " DATE_FORMAT(Created_at, '%Y/%m/%d %H:%i:%s')",
        ])->contain(['TBLMAreaStaff', 'TBLMAreaStaff.TBLMArea'])->where($conditions)->group(['TBLMStaff.StaffID'])->order($order);
    }

    /**
     * @return array|EntityInterface
     */
    public function getStaff($id)
    {
        return $this->find()->select()->where(['ID' => $id])->first();
    }

    /**
     * @return array|EntityInterface
     */
    public function getStaffIDs()
    {
        return $this->find('list', [
            'keyField' => 'StaffID',
            'valueField' => "StaffID"
        ])->where([
            'FlagDelete' => 0
        ]);
    }

    public function findByStaffID($staffID){
        return $this->find()
            ->where([
                'StaffID' => $staffID,
                'FlagDelete' => 0
            ])
            ->first();
    }

}
