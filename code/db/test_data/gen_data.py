from bs4 import BeautifulSoup
from requests import get
import prettify
import random
import datetime
import itertools
from faker import Faker

def main():
    print("informally generating data")
    fake = Faker()
    name = fake.name()

    # generate pokemon and trainers
    pokemon = []
    trainers = []

    # generate trainers
    num_trainers = 10
    for i in range(0,num_trainers):
        dummy_name = fake.name()
        name = "".join(dummy_name.split(" "))
        email = name+str(random.randrange(1000))+"@gmail.com"
        trainers.append({"name": name, "trainer_id": i, "email": email, "phone": phn()})


    data = open("pokemon_species.html").read()
    soup = BeautifulSoup(data, "html.parser")
    name_elements = soup.select("td:nth-child(3) > a")

    trainer_counts = {}
    idx = 1
    tcounter = 1
    for i in name_elements:
        pokemon.append({"pokemon_id": idx, "breedname": i.contents[0], "trainer_id": tcounter, "current_level": random.randrange(100),"nickname": fake.name()}) 
        # print(pokemon[idx])
        if idx % 2 == 0: # assign same trainer id to two pokemon and then update
            tcounter += 1
        idx += 1

    subset_pokemon = pokemon[0:2*num_trainers]
    # print(len(subset_pokemon))
    print(subset_pokemon)

    # business states
    business_states = [{"date_changed": "2020-07-15", "price_per_day": 50.25, "max": 4, "egg: ": 15.75},
                       {"date_changed": "2020-08-15", "price_per_day": 45.25, "max": 4, "egg: ": 20.75},
                       {"date_changed": "2020-09-15","price_per_day":  30.25, "max": 4, "egg: ": 20.75},
                       {"date_changed": "2020-10-02", "price_per_day": 30.25, "max": 4, "egg: ": 20.75}]

    # generate service records 
    dates_only = [] 
    random_dates = {} # maps random date between two date rules to the date rule represented
    for i in business_states: 
        dates_only.append(i["date_changed"])

    # dates_to_generate_per_brule_date = int(len(subset_pokemon)/len(business_states)) # (look at for loop -1 index below, we fix the diff)

    # generate dates to be associated with business states
    for i in range(0,len(dates_only)-1):
        # generates dates in between for each interval 

        start_date = datetime.datetime.strptime(dates_only[i], '%Y-%m-%d')
        end_date = datetime.datetime.strptime(dates_only[i+1], '%Y-%m-%d')
        time_between_dates = end_date - start_date
        days_between_dates = time_between_dates.days

        for i in range(len(subset_pokemon)): # over kill but we will just slice
            random_number_of_days = random.randrange(days_between_dates)
            random_date = start_date + datetime.timedelta(days=random_number_of_days)
            random_dates[random_date.strftime('%Y-%m-%d')] = start_date.strftime('%Y-%m-%d')


    # we generate more random dates that pokemon but just take a slice to fix the lengths

    # get the dictionary to same size as subset pokemon
    N = len(subset_pokemon)
    subset_random_dates = out = dict(itertools.islice(random_dates.items(), N)) # name to make clear
    random_dates = subset_random_dates # only for readability

    # random_dates = random_dates[0:len(subset_pokemon)]
    # print(len(random_dates))
    # print(random_dates)
    # print(len(subset_pokemon))

    # print(dates_only)

    # actually generate service records 
    service_records = []
    idx = 0
    for rand_date in random_dates:
        some_pokemon = subset_pokemon[idx] 
        service_record_id = idx+1
        pokemon_trainer_id = some_pokemon["trainer_id"]
        pokemon_id = some_pokemon["pokemon_id"]
        start_date = rand_date
        start_date_obj = datetime.datetime.strptime(start_date, '%Y-%m-%d')
        checkin_checkout_decide = random.randrange(0,1)
        if checkin_checkout_decide == 1: # inactive/already checked out
            end_date = (start_date_obj + datetime.timedelta(days=random.randrange(0,3))).strftime('%Y-%m-%d')
        else: # make them active
            end_date = "null"
        b_rule_date = random_dates[rand_date]
        # print(start_date, end_date)
        service_records.append({"service_record_id": service_record_id, 
                                "start_time": start_date, 
                                "end_time": end_date, 
                                "pokemon_id": pokemon_id,
                                "trainer_id": pokemon_trainer_id,
                                "bstate_rule_date": b_rule_date})
        idx += 1

    # all are equal
    # there are 2 more times the number of pokemon as there are trainers (at top in variable)
    print(len(service_records)) # number of service records
    print(len(subset_pokemon)) # number of pokemon
    print(len(random_dates)) # number of random dates for service records

    # output trainers
    with open("trainers_data.csv", "w") as f:
        # trainer ids will go to half the size of pokemon (pokemon 20, trainer 10)
        i = 1
        for pok in subset_pokemon:
            trainer_id = pok["trainer_id"]# out
            trainer_idx = pok["trainer_id"]-1 # different from above
            trainer_obj = trainers[trainer_idx]
            print(trainer_obj)

            email = trainer_obj["email"] # out
            phone = trainer_obj["phone"]# out
            name = trainer_obj["name"]# out
            if i % 2 == 0:
                f.write(str(trainer_id) + "," + email + "," + phone + "," + name+"\n")
            i += 1
    
    # output pokemon 
    with open("pokemon_data.csv", "w") as f:
        # trainer ids will go to half the size of pokemon (pokemon 20, trainer 10)
        for pok in subset_pokemon:
            pokemon_id = pok["pokemon_id"]
            trainer_id = pok["trainer_id"]
            current_level = pok["current_level"]
            nickname = pok["nickname"]
            breedname = pok["breedname"]
            f.write(str(pokemon_id) + "," + str(trainer_id) + "," + str(current_level) + "," + nickname + "," + breedname +"\n")
    
    # output service records  
    with open("service_records.csv", "w") as f:
        # trainer ids will go to half the size of pokemon (pokemon 20, trainer 10)
        for r in service_records:
            vals = list(r.values())
            for i in range(len(vals)):
                vals[i] = str(vals[i])
            val_string = ','.join(vals)
            print(val_string)
            f.write(val_string + "\n")

    # output service records  
    with open("business_states.csv", "w") as f:
        # trainer ids will go to half the size of pokemon (pokemon 20, trainer 10)
        for r in business_states:
            vals = list(r.values())
            for i in range(len(vals)):
                vals[i] = str(vals[i])
            val_string = ','.join(vals)
            print(val_string)
            f.write(val_string + "\n")
                
    
    

            

    


def phn():
    n = '0000000000'
    while '9' in n[3:6] or n[3:6]=='000' or n[6]==n[7]==n[8]==n[9]:
        n = str(random.randint(10**9, 10**10-1))
    return n[:3] + '-' + n[3:6] + '-' + n[6:]
    
main()