
                              <?php

                                        if(!empty(checkSubmit($_SESSION['username']))) {
                                        ?>
                                         <div id="errorAlert" class="alert alert-danger alert-dismissible fade in alert-container" >
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <ul id="errorList">
                                            <?php
                                            foreach (checkSubmit($_SESSION['username']) as $error) {
                                               echo "<li>".$error."</li>";
                                            }
                                            ?>
                                            </ul>
                                               </div>
                                        <?php
                                    }


   
                                    ?>


                                       <div class="callout callout-info" style="font-size: 20px; margin: 0; padding: 7px;"> Pernyataan Kebenaran Data</div>
                                        <br>

                                         <div class="form-group">
                                    <label for="tags" class="control-label col-lg-1">&nbsp;</label>
                                    <div class="col-lg-10">


                                                    <p>Dengan ini saya menyatakan bahwa data yang telah saya isikan adalah benar dan sesuai dengan fakta yang ada. Saya bertanggung jawab penuh atas kebenaran data tersebut.</p>
                
              <div class="checkbox checkbox-inline checkbox-success">
                <input type="checkbox" id="is_submit_biodata" name="is_submit_biodata" value="Y" required="" data-msg-requied="Silakan centang pertanyaan" placeholder="Pernyataan">
                <label for="is_submit_biodata" style="padding-left:0"> <b> Saya setuju dengan pernyataan di atas.</b> </label>
              </div>
              
              </div>
          </div><!-- /.form-group -->


                                         <input type="hidden" name="act" value="pernyataan">
                                         <div class="form-group">
                                    <label for="tags" class="control-label col-lg-1">&nbsp;</label>
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> KIRIM DATA</button>
                                       </a>
                                    </div>
                                </div>