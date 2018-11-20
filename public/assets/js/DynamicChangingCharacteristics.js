class AbilityDependency
{
   constructor()
   {
       this.enableDependency         = false;
       this.enableDependencyElement  = document.getElementById('enableDependency');
       this.battleProperties         = [];

       this.battleProperties['battle_firstHero_defence'] = 'battle_firstHero_attack';
       this.battleProperties['battle_firstHero_attack']  = 'battle_firstHero_defence';
       this.battleProperties['battle_secondHero_attack'] = 'battle_secondHero_defence';
       this.battleProperties['battle_secondHero_defence']= 'battle_secondHero_attack';

       this.maxSumOfAbilityElement   = document.getElementById('maxSumOfAbility');
       this.firstHero = {
           'name' : 'battle_firstHero_name',
           'pref' : 'battle_firstHero_'
       };
       this.secondHero = {
           'name' : 'battle_secondHero_name',
           'pref' : 'battle_secondHero_'
       };
   }

   getMaxSumOfAbility()
   {
       return this.maxSumOfAbilityElement.value;
   }

    /**
     *
     * @param   firstElement
     * @param   secondElement
     * @returns {boolean}
     */
   correctBattleElementValue(firstElement, secondElement)
   {
       let maxValue    = this.getMaxSumOfAbility();
       let attackValue = +firstElement.value;

       if(this.enableDependency === true) {
           if (maxValue < attackValue) {
               firstElement.value = maxValue;
               secondElement.value = 0;
           } else {
               secondElement.value = maxValue - attackValue;
               firstElement.value = attackValue;
           }
       }
       return false;
   }

   checkTriggerDependency()
   {
       this.enableDependencyElement.addEventListener('change',(event) => {
           if (event.target.checked) {
               abilityDependency.enableDependency = true;
           } else {
               abilityDependency.enableDependency = false;
           }
       });
   }

   correctBattleElementValues()
   {
       for(let property in abilityDependency.battleProperties) {
           let element = document.getElementById(abilityDependency.battleProperties[property]);

           element.addEventListener('change',(event) => {
               let secondElement = document.getElementById(property);
               abilityDependency.correctBattleElementValue(event.target, secondElement);
               return false;
           });
       }
   }

   runPerformanceChanges()
   {
       this.checkTriggerDependency();

       this.correctBattleElementValues();

       this.proportionalCorrectBattleElementsValue();
       this.changeSelectHeroName();
   }

   setValue(element, value)
   {
       document.getElementById(element).value = value;
   }

   proportionalCorrectBattleElementValue(firstValue, secondValue, element)
   {
       let maxAbilityValue = this.getMaxSumOfAbility();
       let currentAbilityValue = firstValue + secondValue;

       let percentFirstValue   = Math.ceil((firstValue * 100)/ currentAbilityValue);

       let firstElementValue = Math.ceil(maxAbilityValue * percentFirstValue /100);
       let secondElementValue = maxAbilityValue - firstElementValue;

       this.setValue(element, firstElementValue);
       this.setValue(this.battleProperties[element], secondElementValue);
   }

   changeSelectHeroName()
   {
       let selectElements =  document.getElementsByTagName('select');
       let _this = this;

       for(let element of selectElements) {

           element.addEventListener('change', (event) => {
               if(event.target.value.length > 0){
                   $.ajax({
                       url: "/battle/ajax",
                       type: 'POST',
                       dataType : 'json',
                       data : {'id' : + event.target.value },
                       async: true,
                       success: function (data)
                       {
                           let prefix = (event.target.id === _this.firstHero.name) ?
                               _this.firstHero.pref : _this.secondHero.pref;

                           let properties = data.data;

                           for( let item in properties){
                               document.getElementById(prefix + item).value = properties[item];
                           }
                       }
                   })
               }

               return false;
           });
       }
   }

   proportionalCorrectBattleElementsValue()
   {
       this.maxSumOfAbilityElement.addEventListener('change',(event) => {
           let firstValue  = + document.getElementById('battle_firstHero_attack').value;
           let secondValue = + document.getElementById('battle_firstHero_defence').value;
           this.proportionalCorrectBattleElementValue(firstValue, secondValue, 'battle_firstHero_attack');

           firstValue  = + document.getElementById('battle_secondHero_attack').value;
           secondValue = + document.getElementById('battle_secondHero_defence').value;
           this.proportionalCorrectBattleElementValue(firstValue, secondValue, 'battle_secondHero_attack');

       });
   }

}

 let abilityDependency = new AbilityDependency();

 abilityDependency.runPerformanceChanges();

