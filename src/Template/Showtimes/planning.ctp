<div class="showtimes index content">

<!-- Form to select rooms -->
<?php 
    echo $this->Form->create();
    echo $this->Form->control('room_id', ['options' => $room]);
    echo $this->Form->button(__('Click to see the showtimes of this room'));
    echo $this->Form->end(); 
    ?>
    

<table>
<!-- Generating a name day for each columns -->
<thead>
        <tr>
            <th scope="col">Monday</th>
            <th scope="col">Tuesday</th>
            <th scope="col">Wednesday</th>
            <th scope="col">Thursday</th>
            <th scope="col">Friday</th>
            <th scope="col">Saturday</th>
            <th scope="col">Sunday</th>

	</tr>
</thead>
        <tbody>
            <tr>

                <?php for($i=1; $i<8; $i++){ ?>
                    <td>
                        <?php
                         if(isset($showtimesThisWeek[$i])){
                         ?>
                                <?php foreach ($showtimesThisWeek[$i] as $showtimeday): ?>
                                    
                                    <?php echo "<br>" ?>          
                                    <?php echo $showtimeday->movie->name ?>
                                    <?php echo "<br>" ?>
                                    <?php echo "Start at ".$showtimeday->start->format("H:i") ?>
                                    <?php echo "<br>" ?>
                                    <?php echo "End at ".$showtimeday->end->format("H:i") ?>
                                    <?php echo "<br>" ?>
                            
                                <?php endforeach; ?>                 
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
      </tbody>

        
</table>
</div>