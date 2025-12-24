<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Catalog Controller
 *
 * @method \App\Model\Entity\Catalog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CatalogController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $catalog = $this->paginate($this->Catalog);

        $this->set(compact('catalog'));
    }

    /**
     * View method
     *
     * @param string|null $id Catalog id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $catalog = $this->Catalog->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('catalog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $catalog = $this->Catalog->newEmptyEntity();
        if ($this->request->is('post')) {
            $catalog = $this->Catalog->patchEntity($catalog, $this->request->getData());
            if ($this->Catalog->save($catalog)) {
                $this->Flash->success(__('The catalog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The catalog could not be saved. Please, try again.'));
        }
        $this->set(compact('catalog'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Catalog id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $catalog = $this->Catalog->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $catalog = $this->Catalog->patchEntity($catalog, $this->request->getData());
            if ($this->Catalog->save($catalog)) {
                $this->Flash->success(__('The catalog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The catalog could not be saved. Please, try again.'));
        }
        $this->set(compact('catalog'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Catalog id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $catalog = $this->Catalog->get($id);
        if ($this->Catalog->delete($catalog)) {
            $this->Flash->success(__('The catalog has been deleted.'));
        } else {
            $this->Flash->error(__('The catalog could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
