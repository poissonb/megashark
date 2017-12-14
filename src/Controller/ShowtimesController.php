<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Showtimes Controller
 *
 *
 * @method \App\Model\Entity\Showtime[] paginate($object = null, array $settings = [])
 */
class ShowtimesController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $showtimes = $this->paginate($this->Showtimes);

        $this->set(compact('showtimes'));
        $this->set('_serialize', ['showtimes']);
    }

    /**
     * View method
     *
     * @param string|null $id Showtime id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $showtime = $this->Showtimes->get($id, [
            'contain' => []
        ]);

        $this->set('showtime', $showtime);
        $this->set('_serialize', ['showtime']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $showtime = $this->Showtimes->newEntity();
        if ($this->request->is('post')) {
            $showtime = $this->Showtimes->patchEntity($showtime, $this->request->getData());
            if ($this->Showtimes->save($showtime)) {
                $this->Flash->success(__('The showtime has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The showtime could not be saved. Please, try again.'));
        }
        $movies = $this->Showtimes->Movies->find('list', ['limit' => 200]);
        $rooms = $this->Showtimes->Rooms->find('list', ['limit' => 200]);
        $this->set(compact('showtime', 'movies', 'rooms'));
        $this->set('_serialize', ['showtime']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Showtime id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $showtime = $this->Showtimes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $showtime = $this->Showtimes->patchEntity($showtime, $this->request->getData());
            if ($this->Showtimes->save($showtime)) {
                $this->Flash->success(__('The showtime has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The showtime could not be saved. Please, try again.'));
        }
        $this->set(compact('showtime'));
        $this->set('_serialize', ['showtime']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Showtime id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $showtime = $this->Showtimes->get($id);
        if ($this->Showtimes->delete($showtime)) {
            $this->Flash->success(__('The showtime has been deleted.'));
        } else {
            $this->Flash->error(__('The showtime could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function planning($id = null)
    {
        //Retrieve rooms data
        $room = $this->Showtimes->Rooms->find('list');
        
        //Request retrieving showtimes from all rooms
        $Showtimes = $this->Showtimes
            ->find()
            ->contain(['Movies','Rooms'])
            
            ->where(['start >=' => new \DateTime('Monday this week')])
            ->where(['start <=' => new \DateTime('Sunday this week')]);
            
        //If user select a rooms, it's show only the rooms showtimes    
        if ($this->request->is('post')) {
            $Showtimes = $Showtimes
            ->where(['room_id' => $this->request->getData('room_id')]);
        }
            
        //Array for recovering movies data day per day
        $showtimesThisWeek = [];
        
        foreach($Showtimes as $show){
             $showtimesThisWeek[($show->start)->format('N')][] = $show;    
        }
            
            
            
        //Send data to Showtimess      
        $this->set('showtimesThisWeek', $showtimesThisWeek);
        $this->set('Showtimes', $Showtimes);
        $this->set('room', $room);
        $this->set('_serialize', ['room']);
    }
}
