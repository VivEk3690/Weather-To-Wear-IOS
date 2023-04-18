//
//  ViewController.swift
//  WeatherToWear
//
//  Created by Kodeeshwari Solanki on 2023-04-12.
//

import UIKit

class ViewController: UIViewController {
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
        
        navigationItem.setHidesBackButton(true, animated: false)
        Timer.scheduledTimer(timeInterval: 3.0, target: self, selector: #selector(self.goToNextScreen), userInfo: nil, repeats: false)
        
    }
    
    
    @objc func goToNextScreen() {
        
        performSegue(withIdentifier: Segue.toLoginViewController, sender: nil)
        
        self.dismiss(animated: true, completion: nil)
        
    }
}

